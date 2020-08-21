<template>
  <div class="container">
    <div class="row" style="
    overflow: auto;
    height: 300px;
    ">
      <div
        class="col-12 col-sm-12 col-md-6 offset-md-3"
        v-for="(item,index) in compras"
        :key="index"
      >
        <div
          class="cuadro-full br-azul-claro pointer"
          block
          v-b-toggle.v-b-toggle="'accordion-' + index"
          @click="getdetalle(item.id)"
        >
          <p>Numer de Orden: {{item.order_number}}</p>
          <span
            style="text-align: end;
    font-size: 10px;
    font-weight: 100;"
          >{{item.created_at}}</span>
        </div>
        <b-collapse :id="'accordion-' + index" class="cuadro-full">
          <div>
            <p>Numero de Seguimiento: {{item.nseg}}</p>
            <p>Detalle de la compra:</p>
            <ul>
              <li v-for="(det,index) in detalle" :key="index">{{det.titulo}}</li>
            </ul>
          </div>
        </b-collapse>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      compras: {},
      detalle: {},
    };
  },
  created() {
    this.getcompra();
  },
  methods: {
    getcompra() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/order/busqueda/" + user.email)
        .then((response) => {
          this.compras = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getdetalle(id) {
      this.axios
        .get(this.$GlobalUrl + "/api/orden/detalle/" + id)
        .then((response) => {
          this.detalle = response.data.detalle;
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
