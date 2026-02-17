<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import Input from './ui/input/Input.vue'


const emit = defineEmits(['selected'])

const search = ref('')
const users = ref([])
const loading = ref(false)
const showDropdown = ref(false)

watch(search, async (value) => {
    if (value.length < 2) {
        users.value = []
        return
    }

    loading.value = true

    try {
        const response = await axios.get('/user/search', {
            params: { search: value }
        })

        users.value = response.data
        showDropdown.value = true
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
})

const selectUser = (user) => {
    emit('selected', user)
    search.value = ''
    users.value = []
    showDropdown.value = false
}
</script>

<template>
    <div class="relative">
        <Input v-model="search" type="text" placeholder="Buscar usuario por nombre o correo..."
           @focus="showDropdown = true" />

        <div v-if="showDropdown && users.length"
            class="absolute z-10 bg-white border rounded w-full mt-1 max-h-60 overflow-y-auto shadow">
            <div v-for="user in users" :key="user.id" @click="selectUser(user)"
                class="p-2 hover:bg-gray-100 cursor-pointer">
                <p class="font-medium">{{ user.name }}</p>
                <p class="text-sm text-gray-500">{{ user.email }}</p>
            </div>
        </div>

        <div v-if="loading" class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>
</template>
