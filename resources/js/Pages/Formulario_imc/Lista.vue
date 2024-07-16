<script>
export default {
    props: {
        datos: Array
    }
}
</script>


<template>
    <AppLayout title="Dashboard">
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <!-- Campo de búsqueda y botón de exportación -->
                <div class="flex flex-row mb-1 sm:mb-0 justify-between w-full">
                    <h2 class="text-2xl leading-tight">Formulario IMC</h2>
                    <div class="flex">
                        <div class="relative right-0">
                            <input type="text"
                                class="rounded-l-lg border-t border-b border-l text-gray-800 border-gray-200 bg-white h-10 px-5 pr-16 rounded focus:outline-none w-full"
                                v-model="searchTerm" @input="handleSearch" placeholder="Search">
                            <button class="absolute right-0 top-0 mt-2 mr-4">
                                <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                    viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;"
                                    xml:space="preserve" width="512px" height="512px">
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <!-- Card de población total -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6 flex items-center">
                            <svg class="h-8 w-8 text-gray-500 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Población Total</h3>
                                <div class="mt-2 text-sm text-gray-500">Cantidad: {{ total }}</div>
                                <div class="text-sm text-gray-500">Porcentaje: {{ porcentajeTotal }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card de población de hombres -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6 flex items-center">
                            <svg class="h-8 w-8 text-gray-500 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Población de Hombres</h3>
                                <div class="mt-2 text-sm text-gray-500">Cantidad: {{ hombres }}</div>
                                <div class="text-sm text-gray-500">Porcentaje: {{ porcentajeHombres }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Card de población de mujeres -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6 flex items-center">
                            <svg class="h-8 w-8 text-gray-500 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Población de Mujeres</h3>
                                <div class="mt-2 text-sm text-gray-500">Cantidad: {{ mujeres }}</div>
                                <div class="text-sm text-gray-500">Porcentaje: {{ porcentajeMujeres }}%</div>
                            </div>
                        </div>
                    </div>
                </div>






                <!-- Contenedor de dos columnas -->
                <div class="flex">
                    <div class="w-1/5 pr-4">
                        <h2 class="text-2xl leading-tight">Filtros</h2>
                        <!-- Filtro por género -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Filtrar por Género:</label>
                            <select v-model="filtroGenero"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Todos</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <!-- Filtro por rango de edades -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Filtrar por Rango de Edades:</label>
                            <select v-model="filtroEdad"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Todos</option>
                                <option value="15-19">15-19</option>
                                <option value="20-24">20-24</option>
                                <!-- Agrega los demás rangos de edad según tu necesidad -->
                                <option value="80+">80+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna con la tabla de datos -->
                    <div class="w-4/5 pl-4">
                        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <!--                                             <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Id</th>
 -->
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Nombre</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Apellido</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Edad</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Genero</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Peso en libras</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Alturas en cms</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Correo</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                Telefono</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                fecha</th>
                                            <th
                                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="dato in datosPaginados" :key="dato.id">
                                            <!--                                             <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.id }}</td>
 -->
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.nombre }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.apellido }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.edad
                                                }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.genero }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.peso_en_libras }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.altura_en_cms }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.correo }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.telefono }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{
                                                dato.fecha }}</td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ dato.hora
                                                }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Controles de paginación -->
                                <div
                                    class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                                    <span class="text-xs xs:text-sm text-gray-900">
                                        Mostrando {{ (currentPage - 1) * pageSize + 1 }} a {{ Math.min(currentPage *
                                            pageSize, props.datos.length) }} de {{ props.datos.length }} entradas
                                    </span>
                                    <div class="inline-flex mt-2 xs:mt-0">
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-l"
                                            @click="prevPage" :disabled="currentPage === 1">
                                            Ant..
                                        </button>
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-r"
                                            @click="nextPage" :disabled="currentPage === totalPages">
                                            Sig..
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
import { defineProps, ref, computed, watchEffect } from 'vue';
import * as XLSX from 'xlsx';
const props = defineProps(['datos']);

const pageSize = 10; // Tamaño de página ajustado
const currentPage = ref(1);
const searchTerm = ref('');
const filtroGenero = ref('');
const filtroEdad = ref('');
const total = ref(0);
const hombres = ref(0);
const mujeres = ref(0);



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
    let filteredData = props.datos;

    // Aplicar filtro por género
    if (filtroGenero.value) {
        filteredData = filteredData.filter(dato => dato.genero === filtroGenero.value);
    }

    // Aplicar filtro por rango de edades
    if (filtroEdad.value) {
        filteredData = filteredData.filter(dato => {
            const edad = dato.edad;
            switch (filtroEdad.value) {
                case '15-19':
                    return edad >= 15 && edad <= 19;
                case '20-24':
                    return edad >= 20 && edad <= 24;
                // Agrega los demás rangos de edad según tu necesidad
                case '80+':
                    return edad >= 80;
                default:
                    return true;
            }
        });
    }

    // Aplicar filtro de búsqueda general (searchTerm)
    if (searchTerm.value) {
        const lowerCaseSearch = searchTerm.value.toLowerCase();
        filteredData = filteredData.filter(dato =>
            dato.nombre.toLowerCase().includes(lowerCaseSearch) ||
            dato.apellido.toLowerCase().includes(lowerCaseSearch) ||
            dato.correo.toLowerCase().includes(lowerCaseSearch)
            // Añadir más campos según sea necesario
        );
    }

    return filteredData;
});

const datosPaginados = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    return datosFiltrados.value.slice(startIndex, endIndex);
});

const exportData = () => {
    const headers = ['Id', 'Nombre', 'Apellido', 'Edad', 'Genero', 'Peso en libras', 'Alturas en cms', 'Correo', 'Telefono', 'Fecha', 'Hora'];
    const rows = datosFiltrados.value.map(dato => [
        dato.id,
        dato.nombre,
        dato.apellido,
        dato.edad,
        dato.genero,
        dato.peso_en_libras,
        dato.altura_en_cms,
        dato.correo,
        dato.telefono,
        dato.fecha,
        dato.hora
    ]);

    const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Datos Filtrados");

    XLSX.writeFile(workbook, "datos_imc.xlsx");
};


const media = computed(() => {
    const filteredData = datosFiltrados.value.map(dato => dato.edad); // Considera la edad u otro campo numérico
    if (filteredData.length === 0) return 0;
    const sum = filteredData.reduce((acc, curr) => acc + curr, 0);
    return sum / filteredData.length;
});




// Calculando la población total, hombres y mujeres
watchEffect(() => {
    const filteredData = datosFiltrados.value;
    total.value = filteredData.length;
    hombres.value = filteredData.filter(dato => dato.genero === 'Masculino').length;
    mujeres.value = filteredData.filter(dato => dato.genero === 'Femenino').length;
});

const porcentajeTotal = computed(() => (total.value > 0 ? ((total.value / props.datos.length) * 100).toFixed(2) : 0));
const porcentajeHombres = computed(() => (total.value > 0 ? ((hombres.value / props.datos.length) * 100).toFixed(2) : 0));
const porcentajeMujeres = computed(() => (total.value > 0 ? ((mujeres.value / props.datos.length) * 100).toFixed(2) : 0));



</script>
