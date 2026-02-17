<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button'
import InputError from '@/components/InputError.vue';
import { usePage } from '@inertiajs/vue3'
import Alert from '@/components/Alert.vue'
import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    user: Object,
    roles: Array
})

const form = useForm({
    nombre: props.user.name,
    email: props.user.email,
    celular: props.user.phone_number,
    role: props.user.role,
    password: null,
    password_confirmation: null,
})

function submit() {
    form.transform((data) => {
        if (!data.password) {
            delete data.password
            delete data.password_confirmation
        }
        return data
    }).put(`/users/${props.user.id}`)
}

function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/users/${props.user.id}`, {
                preserveScroll: true,
            })
        }
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <Alert v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success" auto-dismiss />
            <Alert v-if="$page.props.errors?.error" :message="$page.props.errors.error" variant="error" auto-dismiss/>

            <h1 class="text-2xl font-bold mb-4">
                Modificar Usuario
            </h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Nombre -->
                <div class="grid gap-1">
                    <Label for="nombre">Nombre</Label>
                    <Input id="nombre" v-model="form.nombre" class="mt-1 block w-full"
                        placeholder="Nombre del invernadero" />
                    <InputError class="mt-1 ms-1" :message="form.errors.nombre" />
                </div>

                <!-- Ubicación -->
                <div class="grid gap-1">
                    <Label for="email">Email</Label>
                    <Input id="email" v-model="form.email" class="mt-1 block w-full" placeholder="usuario@correo.com" />
                    <InputError class="mt-1 ms-1" :message="form.errors.email" />
                </div>

                <!-- Descripción -->
                <div class="grid gap-1">
                    <Label for="celular">No. Celular</Label>

                     <Input id="celular" v-model="form.celular" class="mt-1 block w-full" placeholder="3050000000" />
                    <InputError class="mt-1 ms-1" :message="form.errors.celular" />
                </div>

                 <div class="grid gap-1 mt-5">
                    <Label for="role">Rol</Label>
                    <select
                        id="role"
                        v-model="form.role"
                        class="mt-1 py-1.5 block w-full border border-gray-200 shadow-xs rounded-md focus-visible:ring-blue-500 focus-visible:ring-[2px]"
                    >
                        <option disabled value="">Seleccione un rol</option>
                        <option v-for="role in roles" :key="role.value" :value="role.value">
                            {{ role.label }}
                        </option>
                    </select>
                    <InputError class="mt-1 ms-1" :message="form.errors.role" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="password">Actualizar Contraseña</Label>
                    <Input  type="password" id="password" class="mt-1 block w-full" name="celular" v-model="form.password"
                        placeholder="Ingrese una contraseña" />
                    <InputError class="mt-1 ms-1" :message="form.errors.password" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="password_confirmation">Confirmar Contraseña</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        placeholder="Repite la contraseña"
                    />
                    <InputError class="mt-1 ms-1" :message="form.errors.password_confirmation" />
                </div>

                <!-- Botón -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Guardar cambios
                    </Button>
                </div>

            </form>

            <div class="mt-10">
                <Button type="button" variant="destructive" @click="confirmDelete">
                    Eliminar usuario
                </Button>
            </div>

        </div>
    </AdminLayout>
</template>