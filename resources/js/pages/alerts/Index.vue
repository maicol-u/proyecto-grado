<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import Alert from '@/components/Alert.vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

const layoutComponent = computed(() =>
    page.props.auth.user.role === 'admin' ? AdminLayout : AppLayout,
)

defineProps({
    alerts: Object
})

function formatTriggeredAt(value) {
    if (!value) {
        return 'Sin fecha'
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return value
    }

    return new Intl.DateTimeFormat('es-CO', {
        dateStyle: 'short',
        timeStyle: 'medium',
    }).format(date)
}


function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar alerta?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/alerts/${id}`, {
                preserveScroll: true,
            })
        }
    })
}

</script>

<template>
    <component :is="layoutComponent">
        <div class="p-6">

            <Alert v-if="$page.props.flash?.success" :message="$page.props.flash.success" variant="success"
                auto-dismiss />
            <Alert v-if="$page.props.errors?.error" :message="$page.props.errors.error" variant="error" auto-dismiss />

            <h1 class="text-2xl font-bold mb-4">
                Alertas Generadas
            </h1>

            <div v-if="alerts.length === 0">
                No hay alertas registradas
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-body">
                    <thead class="bg-gray-100 font-medium text-body">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium">Id</th>
                            <th class="px-4 py-2 text-left font-medium">Cultivo</th>
                            <th class="px-4 py-2 text-left font-medium">Sensor</th>
                            <th class="px-4 py-2 text-left font-medium">Tipo</th>
                            <th class="px-4 py-2 text-left font-medium">Fecha Generación</th>
                            <th class="px-4 py-2 text-center font-medium">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="alert in alerts.data" :key="alert.id" class="hover:bg-gray-50">
                            <td class="px-4 py-1">
                                {{ alert.id }}
                            </td>

                            <td class="px-4 py-1">
                                {{ alert.sensor.crop.name }}
                            </td>

                            <td class="px-4 py-1">
                                {{ alert.sensor.name }}
                            </td>

                            <td class="px-4 py-1">
                                {{ alert.message }}
                            </td>

                            <td class="px-4 py-1">
                                {{ formatTriggeredAt(alert.triggered_at) }}
                            </td>

                            <td class="px-4 py-1 text-center space-x-2">

                                <!-- Detalles -->
                                <Link :href="`/alerts/${alert.id}`" class="text-blue-600 hover:underline">
                                    Detalles
                                </Link>

                                <button @click="confirmDelete(alert.id)"
                                    class="text-red-600 hover:underline cursor-pointer">
                                    Eliminar
                                </button>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="flex justify-center gap-2 mt-2">
                <template v-for="link in alerts.links" :key="link.label">
                    <Link v-if="link.url" :href="link.url" v-html="link.label" class="px-3 py-1 border rounded text-sm"
                        :class="{
                            'bg-blue-600 text-white': link.active
                        }" />
                    <span v-else v-html="link.label" class="px-3 py-1 border rounded text-sm text-gray-400" />
                </template>
            </div>
        </div>
    </component>
</template>