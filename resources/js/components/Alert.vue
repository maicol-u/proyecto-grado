<script setup>
import { computed, ref, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    message: { type: String, required: true },
    variant: { type: String, default: 'success' },
    dismissible: { type: Boolean, default: true },
    autoDismiss: { type: Boolean, default: true },
    duration: { type: Number, default: 6000 },
})

const visible = ref(true)
let timeout = null

const variants = {
    success: 'bg-green-100 border-green-300 text-green-800',
    error: 'bg-red-100 border-red-300 text-red-800',
    warning: 'bg-yellow-100 border-yellow-300 text-yellow-800',
    info: 'bg-blue-100 border-blue-300 text-blue-800',
}

const classes = computed(() => variants[props.variant] ?? variants.success)

function hide() {
    visible.value = false
}

function startTimer() {
    if (timeout) clearTimeout(timeout); // Limpiar timer previo si existe
    if (props.autoDismiss) {
        timeout = setTimeout(() => hide(), props.duration)
    }
}

// Reiniciar la alerta si el mensaje cambia (útil para flash messages de Inertia)
watch(() => page.props.flash, () => {
    visible.value = true
    startTimer()
})

onMounted(() => {
    startTimer()
})
</script>

<template>
    <Transition class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="visible" class="mb-4 flex items-start justify-between gap-4 rounded-md border px-4 py-3"
            :class="classes" role="alert">

            <div class="flex items-center gap-2">
                <span class="text-sm font-medium">
                    {{ message }}
                </span>
            </div>

            <button v-if="dismissible" type="button"
                class="text-lg leading-none opacity-60 hover:opacity-100 transition focus:outline-none" @click="hide">
                &times;
            </button>
        </div>
    </Transition>
</template>
