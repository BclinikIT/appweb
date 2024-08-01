<script>
export default {
  props: {
    datos: Array
  }
}

</script>

<template>
    <AppLayout title="Cribado Form cotizacion">
      <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
          <h1 class="text-2xl font-semibold mb-4">Datos del Cribado</h1>
          
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-2 border-b">Nombre Empresa</th>
                  <th class="px-4 py-2 border-b">Dirección</th>
                  <th class="px-4 py-2 border-b">Cantidad colaboradores</th>
                  <th class="px-4 py-2 border-b">Nombre Solicitante</th>
                  <th class="px-4 py-2 border-b">Puesto Empresa</th>
                  <th class="px-4 py-2 border-b">Teléfono directo – móvil</th>
                  <th class="px-4 py-2 border-b">Email</th>
                  <th class="px-4 py-2 border-b">Date</th>
                  <th class="px-4 py-2 border-b">Time</th>
              <!--     <th class="px-4 py-2 border-b">Page URL</th>
                  <th class="px-4 py-2 border-b">User Agent</th>
                  <th class="px-4 py-2 border-b">Remote IP</th>
                  <th class="px-4 py-2 border-b">Powered by</th>
                  <th class="px-4 py-2 border-b">form_id</th>
                  <th class="px-4 py-2 border-b">form_name</th> -->
                </tr>
              </thead>
              <tbody>
                <tr v-for="dato in datosPaginados" :key="dato.id" class="hover:bg-gray-100">
                  <td class="px-4 py-2 border-b">{{ dato.nombre_de_la_empresa }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.direccion }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.cantidad_de_colaboradores_en_total }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.nombre_de_quien_solicita }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.puesto_en_la_empresa }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.telefono_directo_movil }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.email }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.date }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.time }}</td>
                 <!--  <td class="px-4 py-2 border-b">{{ dato.page_url }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.user_agent }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.remote_ip }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.powered_by }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.form_id }}</td>
                  <td class="px-4 py-2 border-b">{{ dato.form_name }}</td> -->
                </tr>
              </tbody>
            </table>
            <div
                                    class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                                    <span class="text-xs xs:text-sm text-gray-900">
                                        Mostrando
                                        {{ (currentPage - 1) * pageSize + 1 }} a
                                        {{
                                            Math.min(
                                                currentPage * pageSize,
                                                props.datos.length
                                            )
                                        }}
                                        de {{ props.datos.length }} entradas
                                    </span>
                                    <div class="inline-flex mt-2 xs:mt-0">
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-l"
                                            @click="prevPage" :disabled="currentPage === 1">
                                            Ant..
                                        </button>
                                        <button
                                            class="text-sm text-indigo-50 transition duration-150 bg-indigo-600 hover:bg-indigo-500 font-semibold py-2 px-4 rounded-r"
                                            @click="nextPage" :disabled="currentPage === totalPages
                                                ">
                                            Sig..
                                        </button>
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
import axios from 'axios';
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
        dato.name.toLowerCase().includes(lowerCaseSearch) ||
        dato.email.toLowerCase().includes(lowerCaseSearch)
        // Añadir más campos según sea necesario
    );
});

const datosPaginados = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    return datosFiltrados.value.slice(startIndex, endIndex);
});

const exportData = () => {
    const headers = ['Id', 'Nombre', 'Correo'];
    const rows = datosFiltrados.value.map(dato => [
        dato.id,
        dato.name,
        dato.email,
    ]);

    const worksheet = XLSX.utils.aoa_to_sheet([headers, ...rows]);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Datos Filtrados");

    XLSX.writeFile(workbook, "User.xlsx");
};

const editar = (dato) => {
    console.log('Editar:', dato);
    // Aquí puedes agregar la lógica para editar el usuario
};

const confirmDelete = (userId) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        axios.delete(route('users.destroy', userId))
            .then(response => {
                console.log('Respuesta del servidor:', response); // Imprimir la respuesta completa
                alert(response.data.message); // Mostrar el mensaje de éxito desde la respuesta
                // Aquí podrías agregar lógica para actualizar la lista de usuarios
                fetchUsers(); // Función para volver a obtener la lista de usuarios
            })
            .catch(error => {
                console.error('Error al eliminar el usuario:', error); // Imprimir el error completo
                alert('Hubo un problema al eliminar el usuario');
            });
    }
};

const fetchUsers = () => {
    axios.get(route('users.index'))
        .then(response => {
            props.datos = response.data; // Suponiendo que la respuesta contiene la lista de usuarios
        })
        .catch(error => {
            console.error('Error al obtener la lista de usuarios:', error);
        });
};


const activar = (dato) => {
    console.log('Activar:', dato);
    // Aquí puedes agregar la lógica para activar/desactivar el usuario
};

onMounted(() => {
    // Lógica de inicialización si es necesaria
});
</script>



  <style scoped>
  /* Estilos adicionales si son necesarios */
  </style>
