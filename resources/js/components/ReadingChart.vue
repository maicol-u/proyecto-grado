<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
)

const props = defineProps({
    sensorId: Number,
    unit: String
})

const readings = ref([])
const loading = ref(true)

// 🔥 Cargar datos desde endpoint
async function loadData() {
    try {
        const res = await axios.get(`/sensors/${props.sensorId}/chart`)
        readings.value = res.data
    } catch (error) {
        console.error('Error cargando lecturas', error)
    } finally {
        loading.value = false
    }
}

onMounted(loadData)

//  Data del gráfico
const chartData = computed(() => ({
    labels: readings.value.map(r => formatDate(r.recorded_at)),
    datasets: [
        {
            label: 'Lecturas',
            data: readings.value.map(r => r.value),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34,197,94,0.2)',
            tension: 0.3,
            pointRadius: 3
        }
    ]
}))

// Opciones
const chartOptions = computed(() => ({
    responsive: true,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            title: {
                display: true,
                text: props.unit
            }
        }
    }
}))

function formatDate(date) {
    return new Date(date).toLocaleTimeString('es-CO', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

onMounted(() => {
    loadData()
    setInterval(loadData, 6000) // cada 6s
})
</script>

<template>
    <div class="bg-white p-4 rounded-lg shadow border">

        <!-- Loading -->
        <div v-if="loading" class="text-center text-gray-500 py-6">
            Cargando gráfico...
        </div>

        <!-- Sin datos -->
        <div v-else-if="!readings.length" class="text-center text-gray-500 py-6">
            No hay datos para graficar
        </div>

        <!-- Gráfico -->
        <Line
            v-else
            :data="chartData"
            :options="chartOptions"
        />
    </div>
</template>