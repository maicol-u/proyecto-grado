<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import SearchSelect from '@/components/SearchSelect.vue'

const props = defineProps({
    sensor: Object,
    crops: Array,
    types: Array,
})

const form = useForm({
    crop_id: props.sensor.crop_id,
    type_id: props.sensor.type_id,
    name: props.sensor.name,
    model: props.sensor.model,
    unit: props.sensor.unit,
    reading_interval: props.sensor.reading_interval,
    min_value: props.sensor.min_value,
    max_value: props.sensor.max_value,
})

function submit() {
    form.put(`/sensors/${props.sensor.id}`);
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <h1 class="text-2xl font-bold mb-4">
                Editar Sensor
            </h1>

            <form @submit.prevent="submit">

                <!-- Nombre -->
                <div class="grid gap-1">
                    <Label for="name">Nombre</Label>
                    <Input id="name" v-model="form.name" />
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
                    <Input id="model" v-model="form.model" />
                    <InputError class="mt-1 ms-1" :message="form.errors.model" />
                </div>

                <!-- Unidad -->
                <div class="grid gap-1 mt-5">
                    <Label for="unit">Unidad</Label>
                    <Input id="unit" v-model="form.unit" />
                    <InputError class="mt-1 ms-1" :message="form.errors.unit" />
                </div>

                <!-- Intervalo -->
                <div class="grid gap-1 mt-5">
                    <Label for="reading_interval">Intervalo de lectura (segundos)</Label>
                    <Input type="number" v-model="form.reading_interval" />
                    <InputError class="mt-1 ms-1" :message="form.errors.reading_interval" />
                </div>

                <!-- Valores -->
                <div class="grid grid-cols-2 gap-4 mt-5">
                    <div class="grid gap-1">
                        <Label>Valor mínimo</Label>
                        <Input type="number" step="0.01" v-model="form.min_value" />
                        <InputError class="mt-1 ms-1" :message="form.errors.min_value" />
                    </div>

                    <div class="grid gap-1">
                        <Label>Valor máximo</Label>
                        <Input type="number" step="0.01" v-model="form.max_value" />
                        <InputError class="mt-1 ms-1" :message="form.errors.max_value" />
                    </div>
                </div>

                <!-- Botón -->
                <div class="flex items-center gap-4 mt-6">
                    <Button :disabled="form.processing">
                        Actualizar Sensor
                    </Button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>