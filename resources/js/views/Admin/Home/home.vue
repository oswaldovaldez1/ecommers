<template>
  <div class="container">
    <div class="row">
      <!-- <div class="cuadro br-rosa" v-for="n in 6" :key="n">
        <span>usuarios</span>
        <p>100</p>
      </div>-->
      <div class="cuadro br-rosa">
        <span>usuarios</span>
        <p>{{datos.totalUsuarios}}</p>
      </div>
    </div>
    <div class="row">
      <!-- <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <line-chart></line-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <pie-chart></pie-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <area-chart></area-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <bar-chart></bar-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <radar-chart></radar-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 h-400">
        <DoughNutChart></DoughNutChart>
      </div>-->

      <div class="col-12 col-sm-12 col-md-6 col-lg-6 h-400 bordes">
        <bar-chart :data="datos.totalComprasMes" v-if="visible"></bar-chart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 h-400 bordes">
        <DoughNutChart :data="datos.totalCompras" v-if="visible"></DoughNutChart>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 offset-md-3 offset-lg-3 bordes-2 h-400">
        <area-chart :data="datos.totalComprasMes" v-if="visible"></area-chart>
      </div>
      <!-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 bordes offset-lg-3 offset-lg-3 h-400">
        <line-chart ></line-chart>
      </div>-->
    </div>
  </div>
</template>

<script>
import LineChart from "../../../components/chart/LineChart";
import AreaChart from "../../../components/chart/AreaChart";
import BarChart from "../../../components/chart/BarChart";
import PieChart from "../../../components/chart/PieChart";
import RadarChart from "../../../components/chart/RadarChart";
import DoughNutChart from "../../../components/chart/DoughNutChart";

export default {
  components: {
    LineChart,
    AreaChart,
    BarChart,
    PieChart,
    RadarChart,
    DoughNutChart,
  },
  data() {
    return {
      datos: {},
      visible: false,
    };
  },
  created() {
    this.getInfo();
  },
  mounted() {},
  methods: {
    getInfo() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/dashboard", {
          headers: {
            Authorization: user.token_type + " " + user.token,
          },
        })
        .then((response) => {
          this.datos = response.data;
          console.log(this.datos);
          this.visible = true;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
</style>
