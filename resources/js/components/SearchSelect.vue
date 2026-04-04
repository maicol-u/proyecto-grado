<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: [String, Number, null],
    options: Array,
    label: {
        type: String,
        default: 'name'
    },
    trackBy: {
        type: String,
        default: 'id'
    },
    placeholder: {
        type: String,
        default: 'Buscar...'
    }
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const open = ref(false)

const filteredOptions = computed(() => {
    return props.options.filter(option =>
        option[props.label].toLowerCase().includes(search.value.toLowerCase())
    )
})

function selectOption(option) {
    emit('update:modelValue', option[props.trackBy])
    search.value = option[props.label]
    open.value = false
}

function handleFocus() {
    open.value = true
}

function handleBlur() {
    setTimeout(() => open.value = false, 150)
}

// Mostrar valor seleccionado al cargar
watch(() => props.modelValue, (value) => {
    const selected = props.options.find(opt => opt[props.trackBy] === value)
    if (selected) {
        search.value = selected[props.label]
    }
}, { immediate: true })
</script>

<template>
    <div class="relative">
        <!-- Input con icono -->
        <div class="relative">
            <input type="text" v-model="search" @focus="handleFocus" @blur="handleBlur" :placeholder="placeholder"
                class="mt-1 py-1 h-10 pl-3 pr-10 block w-full border border-gray-300 rounded-md 
           outline-none focus:outline-none focus:ring-1 focus:ring-blue-500 
           focus:border-blue-500 focus:ring-offset-0 focus:shadow-none
           transition" />

            <!-- Flecha -->
            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>

        <!-- Dropdown -->
        <ul v-if="open" class="absolute z-50 w-full bg-white border border-gray-200 rounded-md mt-1 
               max-h-48 overflow-auto shadow-lg">
            <li v-for="option in filteredOptions" :key="option[trackBy]" @click="selectOption(option)" class="px-3 py-2 cursor-pointer transition
                   hover:bg-green-50 hover:text-green-700" :class="{
                    'bg-green-100 text-green-700 font-medium':
                        option[trackBy] === modelValue
                }">
                {{ option[label] }}
            </li>

            <li v-if="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                No hay resultados
            </li>
        </ul>
    </div>
</template>