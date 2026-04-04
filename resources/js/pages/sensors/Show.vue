<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import ReadingChart from '@/components/ReadingChart.vue'

defineProps({
    sensor: Object,
    readings: Object, // paginado
})

function timeAgo(date) {
    const now = new Date()
    const past = new Date(date)
    const diff = Math.floor((now - past) / 1000)

    // < 1 min
    if (diff < 60) return `hace ${diff} seg`

    // < 1 hora
    if (diff < 3600) return `hace ${Math.floor(diff / 60)} min`

    // < 1 día → Hoy a las HH:mm
    if (diff < 86400) {
        return `Hoy a las ${past.toLocaleTimeString('es-CO', {
            hour: '2-digit',
            minute: '2-digit'
        })}`
    }

    // > 1 día → fecha completa
    return past.toLocaleString('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

</script>

<template>
    <AdminLayout>
        <div class="p-6">

            <!-- Título -->
            <h1 class="text-2xl font-bold mb-6">
                Detalle del Sensor
            </h1>

            <!-- Información en 3 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- Columna 1 -->
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nombre</p>
                        <p class="font-medium">{{ sensor.name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Modelo</p>
                        <p class="font-medium">{{ sensor.model || '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Unidad</p>
                        <p class="font-medium">{{ sensor.unit || '-' }}</p>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Cultivo</p>
                        <p class="font-medium">{{ sensor.crop?.name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tipo</p>
                        <p class="font-medium">{{ sensor.type?.name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Intervalo (seg)</p>
                        <p class="font-medium">{{ sensor.reading_interval }}</p>
                    </div>
                </div>

                <!-- Columna 3 -->
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Valor mínimo</p>
                        <p class="font-medium">{{ sensor.min_value }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Valor máximo</p>
                        <p class="font-medium">{{ sensor.max_value }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <span class="px-2 py-1 rounded text-white text-xs" :class="{
                            'bg-green-500': sensor.status === 'active',
                            'bg-gray-400': sensor.status !== 'active'
                        }">
                            {{ sensor.status }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nivel de alerta</p>
                        <span class="px-2 py-1 rounded text-white text-xs" :class="{
                            'bg-red-500': sensor.alert_level_label === 'ALERTA',
                            'bg-yellow-500': sensor.alert_level_label === 'PRECAUCION',
                            'bg-green-500': sensor.alert_level_label === 'NORMAL'
                        }">
                            {{ sensor.alert_level_label }}
                        </span>
                    </div>
                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Tabla de lecturas -->
                <div>
                    <h2 class="text-lg font-semibold mb-3">
                        Últimas lecturas
                    </h2>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-body">
                            <thead class="bg-gray-100 font-medium text-body">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium">Id</th>
                                    <th class="px-4 py-2 text-left font-medium">Valor</th>
                                    <th class="px-4 py-2 text-left font-medium">Unidad</th>
                                    <th class="px-4 py-2 text-left font-medium">Fecha</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                <tr v-for="reading in readings.data" :key="reading.id" class="hover:bg-gray-50">

                                    <td class="px-4 py-1">
                                        {{ reading.id }}
                                    </td>

                                    <td class="px-4 py-1">
                                        {{ reading.value }}
                                    </td>

                                    <td class="px-4 py-1">
                                        {{ sensor.unit }}
                                    </td>

                                    <td class="px-4 py-1">
                                        {{ timeAgo(reading.recorded_at) }}
                                    </td>

                                </tr>

                                <tr v-if="readings.data.length === 0">
                                    <td colspan="4" class="text-center py-3 text-gray-500">
                                        No hay lecturas registradas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="flex justify-center gap-2 mt-2">
                        <template v-for="link in readings.links" :key="link.label">
                            <Link v-if="link.url" :href="link.url" v-html="link.label"
                                class="px-3 py-1 border rounded text-sm" :class="{
                                    'bg-blue-600 text-white': link.active
                                }" />
                            <span v-else v-html="link.label" class="px-3 py-1 border rounded text-sm text-gray-400" />
                        </template>
                    </div>
                </div>

                <!-- Gráfico -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold mb-3">
                        Gráfico de lecturas
                    </h2>

                    <ReadingChart :sensor-id="sensor.id" :unit="sensor.unit" />
                </div>
            </div>

        </div>
    </AdminLayout>
</template>