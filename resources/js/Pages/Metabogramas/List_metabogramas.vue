<script>
export default {
  props: {
    datos: Array
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
      const year = date.getFullYear();
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      const seconds = String(date.getSeconds()).padStart(2, '0');
      return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    }
  }
}
</script>


<template>
    <AppLayout title="Metabogramas">
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <div class="container mx-auto px-4 sm:px-8">
                    <div class="py-8">
                        <!-- Campo de búsqueda -->
                        <div class="flex flex-row mb-1 sm:mb-0 justify-between w-full">
                            <h2 class="text-2xl leading-tight">Lista </h2>
                            <div class="flex">
                                <div class="relative right-0">
                                    <input type="text"
                                        class="rounded-l-lg border-t border-b border-l text-gray-800 border-gray-200 bg-white h-10 px-5 pr-16 rounded focus:outline-none w-full"
                                        v-model="searchTerm" @input="handleSearch" placeholder="Search">
                                    <button class="absolute right-0 top-0 mt-2 mr-4">
                                        <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px"
                                            y="0px" viewBox="0 0 56.966 56.966"
                                            style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                            width="512px" height="512px">
                                            <path
                                                d="M55.146,51.887l-14.81-14.81c3.486-4.014,5.629-9.254,5.629-14.937C45.964,9.865,35.708,0.61,23.482,0.61   C11.256,0.61,1,9.865,1,22.141c0,12.276,10.256,21.53,22.482,21.53c5.083,0,9.815-1.578,13.684-4.453l14.896,14.896   c0.775,0.775,1.795,1.165,2.813,1.165s2.038-0.39,2.813-1.165C56.696,55.963,56.696,53.438,55.146,51.887z M22.482,37.481   c-8.452,0-15.34-6.858-15.34-15.34c0-8.451,6.888-15.339,15.34-15.339s15.34,6.888,15.34,15.339   C37.822,30.622,30.934,37.481,22.482,37.481z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="relative mb-4">
                                    <button @click="exportData"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Exportar Datos
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Resto de tu código -->
                        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Id
                                            </th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Nombre
                                            </th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Apellido
                                            </th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Correo
                                            </th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Telefono
                                            </th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Fecha y Hora
                                            </th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas de datos -->
                                        <tr v-for="dato in datosPaginados" :key="dato.id">
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.id }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.nombre }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.apellido }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.correo }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.telefono }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ formatDate(dato.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Controles de paginación -->
                                <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                                    <span class="text-xs xs:text-sm text-gray-900">
                                        Mostrando {{ (currentPage - 1) * pageSize + 1 }} a {{ Math.min(currentPage * pageSize, props.datos.length) }} de {{ props.datos.length }} entradas
                                    </span>
                                    <div class="inline-flex mt-2 xs:mt-0">
                                        <!-- Botones de página anterior y siguiente -->
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-l"
                                            @click="prevPage" :disabled="currentPage === 1">
                                            Prev
                                        </button>
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-r"
                                            @click="nextPage" :disabled="currentPage === totalPages">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, ref, computed, onMounted } from 'vue';
import * as XLSX from 'xlsx';
const props = defineProps(['datos']);

const pageSize = 10; // Tamaño de página ajustado
const currentPage = ref(1);
const searchTerm = ref('');

const totalPages = computed(() => {
    const filteredData = datosFiltrados.value;
    return Math.ceil(filteredData.length / pageSize);
});

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const handleSearch = () => {
    currentPage.value = 1; // Reiniciar a la primera página al buscar
};

const datosFiltrados = computed(() => {
    if (!searchTerm.value) {
        return props.datos;
    }
    const lowerCaseSearch = searchTerm.value.toLowerCase();
    return props.datos.filter(dato =>
        dato.nombre.toLowerCase().includes(lowerCaseSearch) ||
        dato.apellido.toLowerCase().includes(lowerCaseSearch) ||
        dato.correo.toLowerCase().includes(lowerCaseSearch)
        // Añadir más campos según sea necesario
    );
});

const datosPaginados = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    return datosFiltrados.value.slice(startIndex, endIndex);
});

const exportData = () => {
    const headers = ['Id', 'Nombre', 'Apellido', 'Correo', 'Telefono', 'Fecha y Hora'];
    const rows = datosFiltrados.value.map(dato => [
        dato.id,
        dato.nombre,
        dato.apellido,
        dato.correo,
        dato.telefono,
        dato.created_at,
    ]);

    const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Datos Filtrados");

    XLSX.writeFile(workbook, "metabograma.xlsx");
};

onMounted(() => {
    // Lógica de inicialización si es necesaria
});
</script>
