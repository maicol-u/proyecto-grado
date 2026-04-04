<script setup>
import { router } from '@inertiajs/vue3'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

defineProps({
    roles: Array
})

const form = useForm({
    nombre: null,
    email: null,
    celular: null,
    role: null,
    password: null,
    password_confirmation: null,
})

function submit() {
    form.post('/users');
}

</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">
                Crear Nuevo Usuario
            </h1>
            <form @submit.prevent="submit">

                <div class="grid gap-1">
                    <Label for="nombre">Nombre</Label>
                    <Input id="nombre" class="mt-1 block w-full" name="nombre" v-model="form.nombre"
                        placeholder="Nombres y apellidos" />                    
                     <InputError class="mt-1 ms-1" :message="form.errors.nombre" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="email">Email Notificaciones</Label>
                    <Input id="email" class="mt-1 block w-full" name="email" v-model="form.email"
                        placeholder="usuario@correo.com" />
                    <InputError class="mt-1 ms-1" :message="form.errors.email" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="celular">Número Celular Notificaciones</Label>
                    <Input id="celular" class="mt-1 block w-full" name="celular" v-model="form.celular"
                        placeholder="3050000000" />
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
                    <Label for="password">Contraseña</Label>
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

                <div class="flex items-center gap-4 mt-5">
                    <Button :disabled="form.processing" data-test="update-profile-button">Guardar</Button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
