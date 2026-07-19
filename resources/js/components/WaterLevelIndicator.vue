<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import axios from 'axios'

const props = defineProps({
    sensorId: {
        type: Number,
        required: true
    },
    unit: {
        type: String,
        default: '%'
    },
    range: {
        type: String,
        default: 'live'
    },
    aggregationMode: {
        type: String,
        default: 'latest'
    },
    averageCount: {
        type: Number,
        default: 1
    },
    title: {
        type: String,
        default: 'Indicador de humedad'
    },
    compact: {
        type: Boolean,
        default: false
    }
})

const readings = ref([])
const loading = ref(true)
const fetchError = ref(false)

function applyIncomingReading(reading) {
    const parsedValue = Number.parseFloat(reading?.value)

    if (!Number.isFinite(parsedValue)) {
        return
    }

    const nextReading = {
        reading_id: reading.reading_id ?? null,
        time: reading.time ?? new Date().toISOString(),
        value: parsedValue,
    }

    const lastReading = readings.value.at(-1)

    if (lastReading?.reading_id && nextReading.reading_id && lastReading.reading_id === nextReading.reading_id) {
        return
    }

    readings.value = [...readings.value, nextReading].slice(-70)
    loading.value = false
    fetchError.value = false
}

function subscribeToSensorChannel() {
    if (!window.Echo) {
        console.warn('Echo no esta disponible. El indicador no recibira actualizaciones en tiempo real.')
        return
    }

    window.Echo.private(`sensor.${props.sensorId}`)
        .listen('.reading.created', (event) => {
            applyIncomingReading(event)
        })
}

async function loadData() {
    try {
        fetchError.value = false

        const response = await axios.get(`/sensors/${props.sensorId}/chart`, {
            params: { range: props.range }
        })

        readings.value = Array.isArray(response.data) ? response.data : []
    } catch (error) {
        fetchError.value = true
        console.error('Error cargando el indicador de humedad', error)
    } finally {
        loading.value = false
    }
}

const normalizedValues = computed(() => readings.value
    .map(reading => Number.parseFloat(reading.value))
    .filter(value => Number.isFinite(value))
    .map(value => Math.min(100, Math.max(0, value))))

const sampleValues = computed(() => {
    if (props.aggregationMode !== 'average') {
        return normalizedValues.value.slice(-1)
    }

    const size = Math.max(1, props.averageCount)
    return normalizedValues.value.slice(-size)
})

const levelValue = computed(() => {
    if (!sampleValues.value.length) {
        return 0
    }

    if (props.aggregationMode !== 'average') {
        return sampleValues.value.at(-1) ?? 0
    }

    const total = sampleValues.value.reduce((sum, value) => sum + value, 0)
    return total / sampleValues.value.length
})

const levelLabel = computed(() => `${Math.round(levelValue.value)}${props.unit}`)

const levelHeight = computed(() => `${levelValue.value}%`)

const aggregationLabel = computed(() => {
    if (props.aggregationMode === 'average') {
        return `Promedio de ${Math.max(1, props.averageCount)} lecturas`
    }

    return 'Ultima lectura'
})

const lastUpdated = computed(() => {
    const lastReading = readings.value.at(-1)

    if (!lastReading?.time) {
        return null
    }

    return new Date(lastReading.time).toLocaleString('es-CO', {
        day: '2-digit',
        month: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
})

onMounted(() => {
    loadData()
    subscribeToSensorChannel()
})

onUnmounted(() => {
    window.Echo?.leave(`sensor.${props.sensorId}`)
})
</script>

<template>
    <section class="indicator-card rounded-3xl border border-slate-200 bg-white shadow-sm" :class="props.compact ? 'p-3' : 'p-5'">
        <div class="flex items-start justify-between gap-3" :class="props.compact ? 'mb-3' : 'mb-4'">
            <div>
                <p class="font-medium uppercase tracking-[0.2em] text-slate-400" :class="props.compact ? 'text-[10px]' : 'text-sm'">
                    Sensor {{ sensorId }}
                </p>
                <h2 class="mt-1 font-semibold text-slate-800" :class="props.compact ? 'text-base' : 'text-xl'">
                    {{ title }}
                </h2>
                <p class="mt-2 text-slate-500" :class="props.compact ? 'text-xs' : 'text-sm'">
                    {{ aggregationLabel }}
                </p>
            </div>

            <div class="rounded-full bg-sky-50 font-semibold text-sky-700" :class="props.compact ? 'px-2 py-1 text-[10px]' : 'px-3 py-1 text-xs'">
                Humedad
            </div>
        </div>

        <div v-if="loading" class="flex items-center justify-center text-slate-500" :class="props.compact ? 'min-h-[170px] text-xs' : 'min-h-[250px] text-sm'">
            Cargando indicador...
        </div>

        <div v-else-if="fetchError" class="flex items-center justify-center text-red-500" :class="props.compact ? 'min-h-[170px] text-xs' : 'min-h-[250px] text-sm'">
            No fue posible cargar las lecturas.
        </div>

        <div v-else-if="!normalizedValues.length" class="flex items-center justify-center text-slate-500" :class="props.compact ? 'min-h-[170px] text-xs' : 'min-h-[250px] text-sm'">
            No hay lecturas disponibles.
        </div>

        <div v-else>
            <div class="flex items-center justify-center">
                <div class="water-gauge" :class="props.compact ? 'water-gauge-compact' : ''">
                    <div class="water-shell">
                        <div class="water-fill" :style="{ height: levelHeight }">
                            <div class="wave wave-back"></div>
                            <div class="wave wave-front"></div>
                        </div>
                        <div class="gauge-gloss"></div>
                    </div>

                    <div class="gauge-value">
                        <span class="value-number">{{ levelLabel }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3" :class="props.compact ? 'mt-3 text-xs' : 'mt-5 text-sm'">
                <div class="rounded-2xl bg-slate-50" :class="props.compact ? 'px-2.5 py-2' : 'px-3 py-2.5'">
                    <p class="text-slate-500">Nivel de humedad</p>
                    <p class="mt-1 font-semibold text-slate-800" :class="props.compact ? 'text-base' : 'text-lg'">{{ levelLabel }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50" :class="props.compact ? 'px-2.5 py-2' : 'px-3 py-2.5'">
                    <p class="text-slate-500">Sensor ID</p>
                    <p class="mt-1 font-semibold text-slate-800" :class="props.compact ? 'text-base' : 'text-lg'">{{ sensorId }}</p>
                </div>
            </div>

            <p v-if="lastUpdated" class="text-center text-slate-400" :class="props.compact ? 'mt-3 text-[11px]' : 'mt-4 text-xs'">
                Actualizado: {{ lastUpdated }}
            </p>
        </div>
    </section>
</template>

<style scoped>
.indicator-card {
    background:
        radial-gradient(circle at top, rgba(186, 230, 253, 0.32), transparent 32%),
        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.water-gauge {
    position: relative;
    display: grid;
    place-items: center;
    width: 190px;
    height: 190px;
}

.water-gauge-compact {
    width: 130px;
    height: 130px;
}

.water-shell {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 9999px;
    border: 8px solid #d7dee7;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
    box-shadow:
        inset 0 14px 30px rgba(255, 255, 255, 0.45),
        inset 0 -12px 20px rgba(15, 23, 42, 0.12);
}

.water-fill {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(180deg, #5fb4ff 0%, #3182ce 100%);
    transition: height 0.9s cubic-bezier(0.22, 1, 0.36, 1);
}

.wave {
    position: absolute;
    top: -12px;
    left: -10%;
    width: 120%;
    height: 28px;
    border-radius: 45%;
    transform-origin: center;
    will-change: transform;
}

.wave-back {
    background: rgba(255, 255, 255, 0.28);
    animation: drift 4.8s ease-in-out infinite;
}

.wave-front {
    top: -8px;
    background: rgba(255, 255, 255, 0.8);
    opacity: 0.7;
    animation: driftReverse 6.2s ease-in-out infinite;
}

.gauge-gloss {
    position: absolute;
    inset: 8% 10% auto;
    height: 34%;
    border-radius: 9999px;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.68) 0%, rgba(255, 255, 255, 0) 100%);
    pointer-events: none;
}

.gauge-value {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    text-align: center;
}

.value-number {
    font-size: 2.2rem;
    line-height: 1;
    font-weight: 300;
    color: #f8fafc;
    text-shadow: 0 3px 12px rgba(15, 23, 42, 0.2);
}

@keyframes drift {
    0% {
        transform: translate3d(-5%, 0, 0) rotate(-1deg) scaleX(1.01);
    }

    25% {
        transform: translate3d(-1.5%, -3px, 0) rotate(0.4deg) scaleX(1.03);
    }

    50% {
        transform: translate3d(3.5%, -1px, 0) rotate(1deg) scaleX(0.99);
    }

    75% {
        transform: translate3d(1%, -4px, 0) rotate(0deg) scaleX(1.02);
    }

    100% {
        transform: translate3d(-5%, 0, 0) rotate(-1deg) scaleX(1.01);
    }
}

@keyframes driftReverse {
    0% {
        transform: translate3d(5%, 0, 0) rotate(0.8deg) scaleX(1);
    }

    20% {
        transform: translate3d(2%, -2px, 0) rotate(0deg) scaleX(1.02);
    }

    50% {
        transform: translate3d(-3.5%, -3px, 0) rotate(-0.8deg) scaleX(1.01);
    }

    80% {
        transform: translate3d(-1%, -1px, 0) rotate(0.2deg) scaleX(0.99);
    }

    100% {
        transform: translate3d(5%, 0, 0) rotate(0.8deg) scaleX(1);
    }
}
</style>