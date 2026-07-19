<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

type Crop = {
    id: number;
    name: string;
    location: string | null;
    sensors_count: number;
};

defineProps<{
    crops: Crop[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-2xl font-semibold">Mis invernaderos</h1>

            <p v-if="crops.length === 0" class="text-sm text-muted-foreground">
                No tienes invernaderos asociados.
            </p>

            <div v-else class="flex gap-4 overflow-x-auto pb-2">
                <Link v-for="crop in crops" :key="crop.id" :href="`/invernadero/${crop.id}/ver`"
                    class="border rounded-lg p-4 shadow cursor-pointer hover:border-sky-600">
                    <h2 class="text-lg font-semibold">
                        {{ crop.name }}
                    </h2>

                    <p class="text-sm text-gray-600">
                        Ubicación: {{ crop.location || 'Sin ubicación registrada' }}
                    </p>

                    <p class="mt-2 text-sm">
                        Sensores vinculados: {{ crop.sensors_count }}
                    </p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
