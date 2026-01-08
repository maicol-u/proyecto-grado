<script setup>
import { router } from '@inertiajs/vue3'
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

const form = useForm({
    nombre: null,
    ubicacion: null,
    descripcion: null,
});

function submit() {
    form.post('/invernadero');
}

</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">
                Crear Nuevo Invernadero
            </h1>
            <form @submit.prevent="submit">

                <div class="grid gap-1">
                    <Label for="nombre">Nombre</Label>
                    <Input id="nombre" class="mt-1 block w-full" name="nombre" v-model="form.nombre"
                        placeholder="Nombre del invernadero" />                    
                     <InputError class="mt-1 ms-1" :message="form.errors.nombre" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="locale">Ubicación</Label>
                    <Input id="locale" class="mt-1 block w-full" name="locale" v-model="form.ubicacion"
                        placeholder="Municipio / Vereda / Kilometro" />
                </div>

                <div class="grid gap-1 mt-5">
                    <Label for="description">Descripción</Label>
                    <textarea id="description" rows="5" name="description" v-model="form.descripcion"
                        class="placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:ring-blue-500 focus-visible:ring-[2px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive mt-1 block w-full">
                    </textarea>

                </div>

                <div class="flex items-center gap-4 mt-5">
                    <Button :disabled="form.processing" data-test="update-profile-button">Guardar</Button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
