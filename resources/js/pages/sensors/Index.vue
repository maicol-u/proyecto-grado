<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import Alert from '@/components/Alert.vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

const page = usePage()

defineProps({
    sensors: Object
})


function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar sensor?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/sensors/${id}`, {
                preserveScroll: true,
            })
        }
    })
}

</script>

<template>
    <AdminLayout>
        <div class="p-6">

            <Alert v-if="$page.props.flash?.success" :message="$page.props.flash.success" variant="success"
                auto-dismiss />
            <Alert v-if="$page.props.errors?.error" :message="$page.props.errors.error" variant="error" auto-dismiss />

            <h1 class="text-2xl font-bold mb-4">
                Lista de sensores
            </h1>
            <div class="my-2">
                <Link href="/sensors/create">
                    <Button type="button" variant="default">
                        Crear Nuevo Sensor
                    </Button>
                </Link>
            </div>

            <div v-if="sensors.length === 0">
                No hay sensores registrados
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-body">
                    <thead class="bg-gray-100 font-medium text-body">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium">Id</th>
                            <th class="px-4 py-2 text-left font-medium">Nombre</th>
                            <th class="px-4 py-2 text-left font-medium">Modelo</th>
                            <th class="px-4 py-2 text-left font-medium">Cultivo</th>
                            <th class="px-4 py-2 text-left font-medium">Estado</th>
                            <th class="px-4 py-2 text-center font-medium">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        <tr v-for="sensor in sensors.data" :key="sensor.id" class="hover:bg-gray-50">
                            <td class="px-4 py-1">
                                {{ sensor.id }}
                            </td>

                            <td class="px-4 py-1">
                                {{ sensor.name }}
                            </td>

                            <td class="px-4 py-1">
                                {{ sensor.model }}
                            </td>

                            <td class="px-4 py-1">
                                {{ sensor.crop_name }}
                            </td>

                            <td class="px-4 py-1 capitalize"
                                :class="sensor.alert_level_label === 'ALERTA' ? 'text-red-600' : ''">
                                {{ sensor.alert_level_label }}
                            </td>

                            <td class="px-4 py-1 text-center space-x-2">

                                <!-- Detalles -->
                                <Link :href="`/sensors/${sensor.id}`" class="text-blue-600 hover:underline">
                                    Detalles
                                </Link>

                                <!-- Editar -->
                                <Link :href="`/sensors/${sensor.id}/edit`" class="text-blue-600 hover:underline">
                                    Editar
                                </Link>

                                <button @click="confirmDelete(sensor.id)"
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
                <template v-for="link in sensors.links" :key="link.label">
                    <Link v-if="link.url" :href="link.url" v-html="link.label" class="px-3 py-1 border rounded text-sm"
                        :class="{
                            'bg-blue-600 text-white': link.active
                        }" />
                    <span v-else v-html="link.label" class="px-3 py-1 border rounded text-sm text-gray-400" />
                </template>
            </div>
        </div>
    </AdminLayout>
</template>