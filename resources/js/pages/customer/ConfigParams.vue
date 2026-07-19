<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, usePage, Head } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import InputError from '@/components/InputError.vue'
import Alert from '@/components/Alert.vue'

const page = usePage()

const props = defineProps({
    user: Object,
})

const form = useForm({
    nombre: props.user.name,
    email: props.user.email,
    celular: props.user.phone_number,
})

function submit() {
    form.put('/client/settings')
}
</script>

<template>
    <Head title="Configuracion" />

    <AppLayout>
        <div class="p-6 md:max-w-2xl">
            <Alert v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success" auto-dismiss />
            <Alert v-if="$page.props.errors?.error" :message="$page.props.errors.error" variant="error" auto-dismiss />

            <h1 class="text-2xl font-bold mb-4">
                Configuración
            </h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Nombre -->
                <div class="grid gap-1">
                    <Label for="nombre">Nombres</Label>
                    <Input id="nombre" v-model="form.nombre" class="mt-1 block w-full"
                        placeholder="Nombre del invernadero" readonly disabled />
                    <InputError class="mt-1 ms-1" :message="form.errors.nombre" />
                </div>

                <!-- Ubicación -->
                <div class="grid gap-1">
                    <Label for="email">Correo Electrónico</Label>
                    <Input id="email" v-model="form.email" class="mt-1 block w-full" placeholder="usuario@correo.com" />
                    <InputError class="mt-1 ms-1" :message="form.errors.email" />
                </div>

                <!-- Descripción -->
                <div class="grid gap-1">
                    <Label for="celular">No. Celular</Label>

                     <Input id="celular" v-model="form.celular" class="mt-1 block w-full" placeholder="3050000000" />
                    <InputError class="mt-1 ms-1" :message="form.errors.celular" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Guardar cambios
                    </Button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>