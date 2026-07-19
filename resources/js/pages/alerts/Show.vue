<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { computed } from 'vue'

const page = usePage()

const layoutComponent = computed(() =>
    page.props.auth.user.role === 'admin' ? AdminLayout : AppLayout,
)

defineProps({
    alert: Object
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
            <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h1 class="mb-4 text-2xl font-semibold text-slate-900 text-gray-800">
                    Información de la Alerta
                </h1>
                <h4 class="mb-6 text-sm text-slate-500">
                    Información sobre la alerta generada por el sistema de monitoreo de humedad del suelo.
                </h4>

                <div class="space-y-3 text-sm text-slate-700">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Id Alerta</span>
                        <span class="font-semibold text-slate-900">{{ alert.id }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Id de Cultivo</span>
                        <span>{{ alert.sensor.crop.id }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Nombre de Cultivo</span>
                        <span>{{ alert.sensor.crop.name }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Id de Sensor</span>
                        <span>{{ alert.sensor.id }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Nombre de Sensor</span>
                        <span>{{ alert.sensor.name }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Valor detectado</span>
                        <span class="rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600">{{ alert.value }}%</span>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                        <span class="font-medium text-slate-500">Tipo de Alerta</span>
                        <span>{{ alert.message }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="font-medium text-slate-500">Fecha de Activación</span>
                        <span>{{ formatTriggeredAt(alert.triggered_at) }}</span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <Button type="button" variant="destructive" @click="confirmDelete(alert.id)">
                        Eliminar Alerta
                    </Button>
                </div>
            </div>
        </div>
    </component>
</template>