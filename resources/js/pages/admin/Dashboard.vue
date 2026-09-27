<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'

defineProps({
  stats: {
    type: Array,
    default: () => [],
  },
  recentAlerts: {
    type: Array,
    default: () => [],
  },
})

function formatDate(value) {
  if (!value) return 'Sin fecha'

  const date = new Date(value)

  if (Number.isNaN(date.getTime())) {
    return value
  }

  return new Intl.DateTimeFormat('es-CO', {
    dateStyle: 'short',
    timeStyle: 'short',
  }).format(date)
}
</script>

<template>
  <AdminLayout>
    <div class="space-y-5 p-6">
      <section class="rounded-2xl border border-slate-200 bg-slate-700 p-3 text-white shadow-sm">
        <h1 class="m-1 text-2xl font-semibold tracking-tight text-white">
          Dashboard Administrador
        </h1>
      </section>

      <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        <article
          v-for="item in stats"
          :key="item.label"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow-md"
        >
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
            {{ item.label }}
          </p>
          <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">
            {{ item.value }}
          </p>
          <p class="mt-2 text-sm leading-5 text-slate-500">
            {{ item.description }}
          </p>
        </article>
      </section>

      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
          <div>
            <p class="text-sm font-medium text-slate-500">Actividad reciente</p>
            <h2 class="mt-1 text-xl font-semibold text-slate-900">Últimas alertas</h2>
          </div>
        </div>

        <div v-if="recentAlerts.length" class="mt-5 space-y-2.5">
          <div
            v-for="alert in recentAlerts"
            :key="alert.id"
            class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">
                {{ alert.message }}
              </p>
              <p class="mt-1 text-sm text-slate-500">
                {{ alert.crop_name }} · {{ alert.sensor_name }}
              </p>
            </div>

            <div class="text-sm text-slate-500 sm:text-right">
              <p class="font-medium text-slate-700">{{ alert.value }}%</p>
              <p class="mt-1">{{ formatDate(alert.triggered_at) }}</p>
            </div>
          </div>
        </div>

        <div v-else class="mt-6 rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-500">
          No hay alertas recientes para mostrar.
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
