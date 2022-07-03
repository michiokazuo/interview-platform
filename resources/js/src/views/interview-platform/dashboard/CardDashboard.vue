<template>
  <b-card
    v-if="data"
    class="earnings-card"
  >
    <b-row>
      <b-col cols="6">
        <b-card-title class="mb-1">
          {{ data.title }}
        </b-card-title>
        <div class="font-small-2">
          Total
        </div>
        <h5 class="mb-1">
          {{ data.total }}
        </h5>
        <b-card-text
          v-for="(label, index) in data.label"
          :key="index"
          class="text-muted font-small-2 mb-0"
        >
          <span class="font-weight-bolder">{{ label }}</span>
          <span>{{ data.data[index] }}</span>
        </b-card-text>
      </b-col>
      <b-col cols="6">
        <!-- chart -->
        <vue-apex-charts
          height="125"
          :options="setting.chartOptions"
          :series="data.data"
        />
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
import {
  BCard, BRow, BCol, BCardTitle, BCardText,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'

export default {
  components: {
    BCard,
    BRow,
    BCol,
    BCardTitle,
    BCardText,
    VueApexCharts,
  },
  props: {
    data: {
      type: Object,
      default: () => {},
    },
    colors: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      setting: {
        chartOptions: {
          chart: {
            type: 'donut',
            toolbar: {
              show: false,
            },
          },
          dataLabels: {
            enabled: true,
            fontSize: '1rem',
            fontFamily: 'Montserrat',
            formatter(val) {
              // eslint-disable-next-line radix
              return `${parseInt(val)}%`
            },
          },
          legend: { show: false },
          labels: this.data.label,
          stroke: { width: 0 },
          colors: this.colors,
          grid: {
            padding: {
              right: -20,
              bottom: -8,
              left: -20,
            },
          },
          plotOptions: {
            pie: {
              donut: {
                labels: {
                  show: true,
                  name: {
                    offsetY: 15,
                  },
                  value: {
                    offsetY: -15,
                    formatter(val) {
                      // eslint-disable-next-line radix
                      return `${parseInt(val)}`
                    },
                  },
                  total: {
                    show: true,
                    offsetY: 15,
                    label: 'Total',
                    formatter() {
                      return 0
                    },
                  },
                },
                colors: {
                  backgroundBarColors: [
                    '#82868b', '#82868b', '#82868b', '#82868b',
                  ],
                },
              },
            },
          },
          responsive: [
            {
              breakpoint: 1325,
              options: {
                chart: {
                  height: 100,
                },
              },
            },
            {
              breakpoint: 1200,
              options: {
                chart: {
                  height: 120,
                },
              },
            },
            {
              breakpoint: 1045,
              options: {
                chart: {
                  height: 100,
                },
              },
            },
            {
              breakpoint: 992,
              options: {
                chart: {
                  height: 120,
                },
              },
            },
          ],
        },
      },
    }
  },
  created() {
    this.setting.chartOptions.plotOptions.pie.donut.labels.total.formatter = () => this.data.total
  },
}
</script>

<style lang="css">
.apexcharts-pie-label {
  font-size: 0.7rem!important;
  font-family: Montserrat!important;
  font-weight: unset!important;
}
</style>
