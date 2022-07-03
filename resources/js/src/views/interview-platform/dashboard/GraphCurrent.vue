<template>
  <b-card
    v-if="data"
    no-body
  >
    <b-card-header>
      <!-- title and subtitle -->
      <div>
        <b-card-title class="mb-1">
          {{ data.title }}
        </b-card-title>
        <b-card-sub-title>current 7 days</b-card-sub-title>
      </div>
      <!--/ title and subtitle -->
    </b-card-header>

    <b-card-body>
      <vue-apex-charts
        type="line"
        height="400"
        :options="lineChartSimple.chartOptions"
        :series="lineChartSimple.series"
      />
    </b-card-body>
  </b-card>
</template>

<script>
import {
  BCard, BCardBody, BCardHeader, BCardTitle, BCardSubTitle,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import { $themeColors } from '@themeConfig'

export default {
  components: {
    VueApexCharts,
    BCardHeader,
    BCard,
    BCardBody,
    BCardTitle,
    BCardSubTitle,
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
    categories: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      lineChartSimple: {
        series: [],
        chartOptions: {
          chart: {
            zoom: {
              enabled: false,
            },
            toolbar: {
              show: false,
            },
          },
          markers: {
            strokeWidth: 7,
            strokeOpacity: 1,
            strokeColors: [$themeColors.light],
            colors: [$themeColors.warning],
          },
          colors: [$themeColors.warning],
          dataLabels: {
            enabled: false,
          },
          stroke: {
            curve: 'straight',
          },
          grid: {
            xaxis: {
              lines: {
                show: true,
              },
            },
          },
          tooltip: {
            custom(data) {
              return `${'<div class="px-1 py-50"><span>'}${
                data.series[data.seriesIndex][data.dataPointIndex]
              }</span></div>`
            },
          },
          xaxis: {
            categories: [],
          },
          yaxis: {
            labels: {
              formatter(val) {
                return val.toFixed(0)
              },
            },
          },
        },
      },
    }
  },
  created() {
    const dataTmp = this.data.data ?? {}
    // eslint-disable-next-line no-restricted-syntax
    for (const key in dataTmp) {
      if (Object.prototype.hasOwnProperty.call(dataTmp, key)) {
        this.lineChartSimple.series.push({
          name: key[0].toUpperCase() + key.slice(1).toLowerCase(),
          data: dataTmp[key],
        })
      }
    }

    this.lineChartSimple.chartOptions.xaxis.categories = this.categories
    this.lineChartSimple.chartOptions.colors = this.colors
  },
}
</script>
