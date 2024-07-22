
<template>
    <div id="chart">
      <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const colors = ['#FF5733', '#33FF57', '#3357FF', '#F5FF33', '#FF33A8', '#FF8C33', '#33FFF5', '#8C33FF'];

  const series = ref([{
    data: [21, 22, 10, 28, 16, 21, 13, 30]
  }]);

  const chartOptions = ref({
    chart: {
      height: 350,
      type: 'bar',
      events: {
        click: function(chart, w, e) {
          // console.log(chart, w, e)
        }
      }
    },
    colors: colors,
    plotOptions: {
      bar: {
        columnWidth: '45%',
        distributed: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    legend: {
      show: false
    },
    xaxis: {
      categories: [
        ['John', 'Doe'],
        ['Joe', 'Smith'],
        ['Jake', 'Williams'],
        'Amber',
        ['Peter', 'Brown'],
        ['Mary', 'Evans'],
        ['David', 'Wilson'],
        ['Lily', 'Roberts'],
      ],
      labels: {
        style: {
          colors: colors,
          fontSize: '12px'
        }
      }
    }
  });
  </script>

  <style scoped>
  /* Puedes agregar estilos personalizados aquí si es necesario */
  </style>
