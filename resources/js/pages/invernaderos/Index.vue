<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import Alert from '@/components/Alert.vue'

const page = usePage()

defineProps({
  invernaderos: Array
})

</script>

<template>
  <AdminLayout>
    <div class="p-6">

      <Alert v-if="$page.props.flash?.success" :message="$page.props.flash.success" variant="success" auto-dismiss />

      <h1 class="text-2xl font-bold mb-4">
        Lista de Invernaderos
      </h1>
      <div class="my-2">
        <Link href="/invernadero/create">
          <Button type="button" variant="default">
            Crear Invernadero
          </Button>
        </Link>
      </div>

      <div v-if="invernaderos.length === 0">
        No hay invernaderos registrados
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Link v-for="inv in invernaderos" :key="inv.id" :href="`/invernadero/${inv.id}/edit`" class="block">
          <div class="border rounded-lg p-4 shadow cursor-pointer hover:border-sky-600">

            <h2 class="text-lg font-semibold">
              {{ inv.nombre }}
            </h2>

            <p class="text-sm text-gray-600">
              Ubicación: {{ inv.ubicacion }}
            </p>

            <p class="text-sm">
              Usuarios vinculados: {{ inv.usuarios.length }}
            </p>

          </div>
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>