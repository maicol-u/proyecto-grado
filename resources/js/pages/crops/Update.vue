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
import { ref } from 'vue'
import UserAutocomplete from '@/components/UserAutocomplete.vue';


const page = usePage()

const props = defineProps({
    invernadero: Object,
    users: Array
})

const form = useForm({
    nombre: props.invernadero.name,
    ubicacion: props.invernadero.location,
    descripcion: props.invernadero.description,
})

function submit() {
    form.put(`/invernadero/${props.invernadero.id}`)
}

function confirmDelete() {
    Swal.fire({
        title: '¿Eliminar invernadero?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/invernadero/${props.invernadero.id}`, {
                preserveScroll: true,
            })
        }
    })
}

const selectedUser = ref("")

const attachUser = (user) => {
    router.post(`/invernadero/${props.invernadero.id}/vincular`, {
        user_id: user.id
    }, {
        preserveScroll: true
    })

}

const distachUser = (userId) => {
    router.delete(
        `/invernaderos/${props.invernadero.id}/users/${userId}`,
        {
            preserveScroll: true
        }
    )
}

</script>

<template>
    <AdminLayout>
        <div class="p-6 md:max-w-2xl">
            <Alert v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success"
                auto-dismiss />

            <h1 class="text-2xl font-bold mb-4">
                Modificar Invernadero
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
                    <Label for="ubicacion">Ubicación</Label>
                    <Input id="ubicacion" v-model="form.ubicacion" class="mt-1 block w-full" placeholder="Ubicación" />
                    <InputError class="mt-1 ms-1" :message="form.errors.ubicacion" />
                </div>

                <!-- Descripción -->
                <div class="grid gap-1">
                    <Label for="descripcion">Descripción</Label>

                    <textarea id="descripcion" rows="5" name="descripcion" v-model="form.descripcion"
                        placeholder="Descripción (opcional)"
                        class="placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:ring-blue-500 focus-visible:ring-[2px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive mt-1 block w-full">
                    </textarea>
                    <InputError class="mt-1 ms-1" :message="form.errors.descripcion" />
                </div>

                <!-- Botón -->
                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Guardar cambios
                    </Button>
                </div>

            </form>

            <!-- Usuarios vinculados -->
            <div class="shadow-md p-2 border rounded-lg mt-6">
                <h2 class="text-lg font-semibold my-1 mt-2 text-gray-800">
                    Vincular usuario:
                </h2>

                <div class="mb-4">
                    <UserAutocomplete @selected="attachUser"> </UserAutocomplete>
                </div>


                <h2 class="text-lg font-semibold my-1 mt-2 text-gray-800">
                    Lista de usuarios vinculados
                </h2>
                <div v-if="invernadero.users.length" class="space-y-3">
                    <div v-for="user in invernadero.users" :key="user.id"
                        class="flex justify-between items-center p-3 border rounded-md bg-gray-50">
                        <div>
                            <p class="font-medium">
                                {{ user.name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ user.email }}
                            </p>
                        </div>

                        <button @click="distachUser(user.id)" class="text-red-600 hover:text-red-800 text-sm cursor-pointer">
                            Desvincular
                        </button>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">
                    No hay usuarios vinculados.
                </p>
            </div>


            <div class="mt-10">
                <Button type="button" variant="destructive" @click="confirmDelete">
                    Eliminar Invernadero
                </Button>
            </div>

        </div>
    </AdminLayout>
</template>