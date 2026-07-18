<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type AppPageProps, type BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage<AppPageProps>();

const layoutComponent = computed(() =>
    page.props.auth.user.role === 'admin' ? AdminLayout : AppLayout,
);
</script>

<template>
    <component :is="layoutComponent" :breadcrumbs="breadcrumbs">
        <slot />
    </component>
</template>