<template>
  <b-card
    v-if="graph"
    no-body
  >
    <b-card-header>
      <!-- title and legend -->
      <div class="mb-1">
        <b-card-title class="mb-1">
          {{ graph.title }}
        </b-card-title>
        <b-card-sub-title>Interview & Practice & News</b-card-sub-title>
      </div>
      <!--/ title and legend -->
      <!-- button group -->
      <b-button-group class="mb-1">
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          :variant="type === 'daily' ? 'primary' : 'outline-primary'"
          @click.prevent="changeGraph('daily')"
        >
          Daily
        </b-button>
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          :variant="type === 'monthly' ? 'primary' : 'outline-primary'"
          @click.prevent="changeGraph('monthly')"
        >
          Monthly
        </b-button>
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          :variant="type === 'yearly' ? 'primary' : 'outline-primary'"
          @click.prevent="changeGraph('yearly')"
        >
          Yearly
        </b-button>
      </b-button-group>
      <!--/ button group -->
      <!-- datepicker -->
      <div class="d-flex align-items-center mb-1">
        <feather-icon
          icon="CalendarIcon"
          size="16"
        />
        <flat-pickr
          v-model="rangePicker"
          :config="{ mode: 'range'}"
          class="form-control flat-picker bg-transparent border-0 shadow-none"
          placeholder="YYYY-MM-DD"
          style="min-width: 210px;"
        />
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          variant="primary"
          @click.prevent="filterDate()"
        >
          Filter
        </b-button>
      </div>
    <!-- datepicker -->
    </b-card-header>

    <b-card-body>
      <vue-apex-charts
        type="bar"
        height="400"
        :options="columnChart.chartOptions"
        :series="columnChart.series"
      />
    </b-card-body>
  </b-card>
</template>

<script>
import {
  BCard, BCardBody, BCardHeader, BCardTitle, BCardSubTitle, BButton, BButtonGroup,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import dashboard from '@/store/api/Dashboard'

export default {
  components: {
    BCard,
    BCardBody,
    BCardHeader,
    VueApexCharts,
    flatPickr,
    BCardTitle,
    BCardSubTitle,
    BButton,
    BButtonGroup,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      type: 'daily',
      rangePicker: [
        new Date(new Date().setDate(new Date().getDate() - 7)),
        new Date(),
      ],
      graph: null,
      columnChart: {
        series: [],
        chartOptions: {
          chart: {
            stacked: true,
            toolbar: {
              show: false,
            },
          },
          colors: ['#826af9', '#d2b0ff', '#f8d3ff'],
          plotOptions: {
            bar: {
              columnWidth: '15%',
            },
          },
          dataLabels: {
            enabled: false,
          },
          legend: {
            show: true,
            position: 'top',
            fontSize: '14px',
            fontFamily: 'Montserrat',
            horizontalAlign: 'left',
          },
          stroke: {
            show: true,
            colors: ['transparent'],
          },
          grid: {
            xaxis: {
              lines: {
                show: true,
              },
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
          fill: {
            opacity: 1,
          },
        },
      },
    }
  },
  created() {
    this.getStatisticInterview()
  },
  methods: {
    getStatisticInterview() {
      const time = Array.isArray(this.rangePicker) ? this.rangePicker : this.rangePicker?.split(' to ') ?? []
      dashboard.graphAdmin({
        type: this.type,
        start: time[0] ?? null,
        end: time[1] ?? time[0] ?? null,
      }).then(resp => {
        const rs = resp.data
        this.graph = rs.data

        const dataTmp = this.graph.interviews ?? {}
        this.columnChart.series = []
        // eslint-disable-next-line no-restricted-syntax
        for (const key in dataTmp) {
          if (Object.prototype.hasOwnProperty.call(dataTmp, key)) {
            this.columnChart.series.push({
              name: key[0].toUpperCase() + key.slice(1).toLowerCase(),
              data: dataTmp[key],
            })
          }
        }

        this.columnChart.chartOptions = {
          ...this.columnChart.chartOptions,
          xaxis: {
            categories: [...this.graph.categories],
          },
        }
      }).catch(err => {
        console.log(err)
        this.graph = null
      })
    },
    changeGraph(type) {
      this.type = type
      this.getStatisticInterview()
    },
    filterDate() {
      this.getStatisticInterview()
    },
  },
}
</script>
<style lang="scss" scoped>
@import '~@core/scss/vue/libs/vue-flatpicker.scss';
@import '~@core/scss/vue/libs/chart-apex.scss';
</style>
