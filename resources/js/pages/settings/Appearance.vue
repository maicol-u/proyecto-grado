<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import SettingsPageLayout from '@/layouts/settings/PageLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/appearance';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configuración de Apariencia',
        href: edit().url,
    },
];
</script>

<template>
    <SettingsPageLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configuración de Apariencia" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Configuración de Apariencia"
                    description="Actualiza la configuración de apariencia de tu cuenta"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </SettingsPageLayout>
</template>
