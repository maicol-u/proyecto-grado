<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import Alert from '@/components/Alert.vue'

const page = usePage()

defineProps({
    users: Array
})

</script>

<template>
    <AdminLayout>
        <div class="p-6">

            <Alert v-if="$page.props.flash?.success" :message="$page.props.flash.success" variant="success"
                auto-dismiss />

            <h1 class="text-2xl font-bold mb-4">
                Lista de usuarios
            </h1>
            <div class="my-2">
                <Link href="/users/create">
                    <Button type="button" variant="default">
                        Crear Nuevo Usuario
                    </Button>
                </Link>
            </div>

            <div v-if="users.length === 0">
                No hay usuarios registrados
            </div>

               <!-- Tabla -->
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-body">
                <thead class="bg-gray-100 font-medium text-body">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">Nombre</th>
                        <th class="px-4 py-2 text-left font-medium">Email</th>
                        <th class="px-4 py-2 text-left font-medium">No. Celular</th>
                        <th class="px-4 py-2 text-left font-medium">Rol</th>
                        <th class="px-4 py-2 text-center font-medium">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    <tr
                        v-for="user in users.data"
                        :key="user.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-4 py-1">
                            {{ user.name }}
                        </td>

                        <td class="px-4 py-1">
                            {{ user.email }}
                        </td>

                        <td class="px-4 py-1">
                            {{ user.phone_number }}
                        </td>

                        <td class="px-4 py-1 capitalize">
                            {{ user.role_label }}
                        </td>

                        <td class="px-4 py-1 text-center space-x-2">

                            <!-- Editar -->
                            <Link
                                :href="`/users/${user.id}/edit`"
                                class="text-blue-600 hover:underline"
                            >
                                Editar
                            </Link>

                            <!-- Eliminar -->
                            <Link
                                :href="`/users/${user.id}`"
                                method="delete"
                                as="button"
                                class="text-red-600 hover:underline"
                                preserve-scroll
                            >
                                Eliminar
                            </Link>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="flex justify-center gap-2 mt-2">
            <template v-for="link in users.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border rounded text-sm"
                    :class="{
                        'bg-blue-600 text-white': link.active
                    }"
                />
                <span
                    v-else
                    v-html="link.label"
                    class="px-3 py-1 border rounded text-sm text-gray-400"
                />
            </template>
        </div>
        </div>
    </AdminLayout>
</template>