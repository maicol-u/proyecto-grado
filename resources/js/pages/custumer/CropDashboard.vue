<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import WaterLevelIndicator from '@/components/WaterLevelIndicator.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

type Sensor = {
    id: number;
    name: string;
    model: string | null;
    unit: string | null;
    status: string;
};

type Crop = {
    id: number;
    name: string;
    location: string | null;
    description: string | null;
    is_active: boolean | number | null;
    sensors_count: number;
    sensors: Sensor[];
};

const props = defineProps<{
    crop: Crop;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: props.crop.name,
        href: `/invernadero/${props.crop.id}/ver`,
    },
];

function cropStatusLabel(status: Crop['is_active']) {
    return status === false || status === 0 ? 'Inactivo' : 'Activo';
}

function cropStatusClass(status: Crop['is_active']) {
    return status === false || status === 0 ? 'bg-gray-500' : 'bg-green-500';
}

function sensorStatusLabel(status: string) {
    return status === 'active' ? 'Activo' : 'Inactivo';
}

function sensorStatusClass(status: string) {
    return status === 'active' ? 'bg-green-500' : 'bg-gray-500';
}
</script>

<template>
    <Head :title="crop.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-bold">
                Detalle del Invernadero
            </h1>

            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nombre</p>
                        <p class="font-medium">{{ crop.name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Ubicacion</p>
                        <p class="font-medium">{{ crop.location || '-' }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Descripcion</p>
                        <p class="font-medium">{{ crop.description || '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Sensores vinculados</p>
                        <p class="font-medium">{{ crop.sensors_count }}</p>
                    </div>
                </div>

            </div>

            <div>
                <h2 class="mb-3 text-lg font-semibold">Sensores del invernadero</h2>

                <div v-if="crop.sensors.length === 0" class="rounded-lg border p-4 text-sm text-gray-500">
                    Este invernadero no tiene sensores registrados.
                </div>

                <div v-else class="flex gap-4 overflow-x-auto pb-2">
                    <Link
                        v-for="sensor in crop.sensors"
                        :key="sensor.id"
                        :href="`/sensors/${sensor.id}`"
                        class="block min-w-[280px] max-w-[280px] rounded-lg p-3 transition"
                    >
                        <WaterLevelIndicator
                            :sensor-id="sensor.id"
                            :unit="sensor.unit || '%'"
                            title="Nivel actual"
                            aggregation-mode="latest"
                            :average-count="1"
                            compact
                        />
                    </Link>
                </div>
            </div>

            <div class="mt-6">
                <Link href="/dashboard" class="text-sm text-sky-700 hover:underline">
                    Volver al dashboard
                </Link>
            </div>
        </div>
    </AppLayout>
</template>