<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.css'
import { watch, ref } from 'vue'
import SearchSelect from '@/components/SearchSelect.vue'

defineProps({
    crops: Array,
    types: Array,
})

const form = useForm({
    crop_id: '',
    type_id: '',
    name: '',
    model: '',
    unit: '',
    reading_interval: '',
    min_value: '',
    max_value: '',
})

function submit() {
    form.post('/sensors');
}

const selectedCrop = ref(null)

watch(selectedCrop, (value) => {
    form.crop_id = value?.id ?? null
})
</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">
                Crear Sensor
            </h1>

            <form @submit.prevent="submit">

                <!-- Nombre -->
                <div class="grid gap-1">
                    <Label for="name">Nombre</Label>
                    <Input id="name" v-model="form.name" placeholder="Ej: Sensor Humedad 1" />
                    <InputError class="mt-1 ms-1" :message="form.errors.name" />
                </div>

                <!-- Cultivo -->
                <div class="grid gap-1 mt-5">
                    <Label for="crop_id">Cultivo</Label>

                    <SearchSelect v-model="form.crop_id" :options="crops" label="name" track-by="id"
                        placeholder="Buscar cultivo..." />

                    <InputError class="mt-1 ms-1" :message="form.errors.crop_id" />
                </div>

                <!-- Tipo de sensor -->
                <div class="grid gap-1 mt-5">
                    <Label for="type_id">Tipo de sensor</Label>
                    <SearchSelect v-model="form.type_id" :options="types" label="name" track-by="id"
                        placeholder="Buscar tipo..." />
                    <InputError class="mt-1 ms-1" :message="form.errors.type_id" />
                </div>

                <!-- Modelo -->
                <div class="grid gap-1 mt-5">
                    <Label for="model">Modelo</Label>
                    <Input id="model" v-model="form.model" placeholder="Ej: DHT11" />
                    <InputError class="mt-1 ms-1" :message="form.errors.model" />
                </div>

                <!-- Unidad -->
                <div class="grid gap-1 mt-5">
                    <Label for="unit">Unidad</Label>
                    <Input id="unit" v-model="form.unit" placeholder="Ej: °C, %, ppm" />
                    <InputError class="mt-1 ms-1" :message="form.errors.unit" />
                </div>

                <!-- Intervalo -->
                <div class="grid gap-1 mt-5">
                    <Label for="reading_interval">Intervalo de lectura (segundos)</Label>
                    <Input type="number" id="reading_interval" v-model="form.reading_interval" />
                    <InputError class="mt-1 ms-1" :message="form.errors.reading_interval" />
                </div>

                <!-- Valores mínimos y máximos -->
                <div class="grid grid-cols-2 gap-4 mt-5">
                    <div class="grid gap-1">
                        <Label for="min_value">Valor mínimo</Label>
                        <Input type="number" step="0.01" id="min_value" v-model="form.min_value" />
                        <InputError class="mt-1 ms-1" :message="form.errors.min_value" />
                    </div>

                    <div class="grid gap-1">
                        <Label for="max_value">Valor máximo</Label>
                        <Input type="number" step="0.01" id="max_value" v-model="form.max_value" />
                        <InputError class="mt-1 ms-1" :message="form.errors.max_value" />
                    </div>
                </div>

                <!-- Botón -->
                <div class="flex items-center gap-4 mt-6">
                    <Button :disabled="form.processing">
                        Guardar Sensor
                    </Button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
