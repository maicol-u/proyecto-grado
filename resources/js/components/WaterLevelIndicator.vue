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
        default: 5
    },
    refreshInterval: {
        type: Number,
        default: 5000
    },
    title: {
        type: String,
        default: 'Indicador de humedad'
    }
})

const readings = ref([])
const loading = ref(true)
const fetchError = ref(false)
let intervalId = null

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
    intervalId = setInterval(loadData, props.refreshInterval)
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <section class="indicator-card rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-slate-400">
                    Sensor {{ sensorId }}
                </p>
                <h2 class="mt-1 text-xl font-semibold text-slate-800">
                    {{ title }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    {{ aggregationLabel }}
                </p>
            </div>

            <div class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                Humedad
            </div>
        </div>

        <div v-if="loading" class="flex min-h-[250px] items-center justify-center text-sm text-slate-500">
            Cargando indicador...
        </div>

        <div v-else-if="fetchError" class="flex min-h-[250px] items-center justify-center text-sm text-red-500">
            No fue posible cargar las lecturas.
        </div>

        <div v-else-if="!normalizedValues.length" class="flex min-h-[250px] items-center justify-center text-sm text-slate-500">
            No hay lecturas disponibles.
        </div>

        <div v-else>
            <div class="flex items-center justify-center">
                <div class="water-gauge">
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

            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-2xl bg-slate-50 px-3 py-2.5">
                    <p class="text-slate-500">Nivel de humedad</p>
                    <p class="mt-1 text-lg font-semibold text-slate-800">{{ levelLabel }}</p>
                </div>

                <div class="rounded-2xl bg-slate-50 px-3 py-2.5">
                    <p class="text-slate-500">Sensor ID</p>
                    <p class="mt-1 text-lg font-semibold text-slate-800">{{ sensorId }}</p>
                </div>
            </div>

            <p v-if="lastUpdated" class="mt-4 text-center text-xs text-slate-400">
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
    transition: height 0.5s ease;
}

.wave {
    position: absolute;
    top: -12px;
    left: -10%;
    width: 120%;
    height: 28px;
    border-radius: 45%;
}

.wave-back {
    background: rgba(255, 255, 255, 0.28);
    animation: drift 8s linear infinite;
}

.wave-front {
    top: -8px;
    background: rgba(255, 255, 255, 0.8);
    opacity: 0.7;
    animation: driftReverse 6s linear infinite;
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
    from {
        transform: translateX(-4%);
    }

    to {
        transform: translateX(4%);
    }
}

@keyframes driftReverse {
    from {
        transform: translateX(5%);
    }

    to {
        transform: translateX(-5%);
    }
}
</style>