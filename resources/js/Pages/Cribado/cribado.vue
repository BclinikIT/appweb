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
                <tr v-for="dato in datos" :key="dato.id" class="hover:bg-gray-100">
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
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';


  const props = defineProps(['datos']);

  console.log(props.datos);
  </script>

  <style scoped>
  /* Estilos adicionales si son necesarios */
  </style>
