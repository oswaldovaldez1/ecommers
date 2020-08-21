<template>
  <div class="container">
    <button id="actualizar" @click="clickActu" class="btn btn-outline-rosa">Actualizar</button>
    <div class="py-3">
      <b-progress
        :value="valor"
        :max="busquedaact.length"
        show-value
        :variant="getVariant"
        class="mb-3 w-50"
        striped
        show-progress
        animated
      ></b-progress>
      <b-progress
        :value="valorData"
        :max="resultado.length"
        show-value
        class="mb-3"
        striped
        show-progress
        animated
        :variant="getVariante"
      ></b-progress>
      <p v-if="resultado.length > 0">{{ (valorData+valor) + " de " + resultado.length }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      busquedas: ["--"],
      resultado: [],
      busquedaact: [],
      valueData: 0,
      valorData: 0,
      valor: 0,
      variante: "danger"
    };
  },
  computed: {
    getVariante() {
      if (this.resultado.length === 0) return "danger";
      else if ((this.valorData / this.resultado.length) * 100 <= 33) {
        return "danger";
      } else if ((this.valorData / this.resultado.length) * 100 <= 66) {
        return "warning";
      } else if ((this.valorData / this.resultado.length) * 100 <= 100) {
        return "success";
      }
    },
    getVariant() {
      if ((this.valor / this.busquedaact.length) * 100 <= 33) {
        return "danger";
      } else if ((this.valor / this.busquedaact.length) * 100 <= 66) {
        return "warning";
      } else if ((this.valor / this.busquedaact.length) * 100 <= 100) {
        return "success";
      }
    }
  },
  methods: {
    clickActu() {
      if (
        document.querySelector("#actualizar").classList.contains("disabled")
      ) {
        return;
      }
      this.actualizar();
    },
    actualizar() {
      document.querySelector("#actualizar").classList.add("disabled");
      this.valorData = this.valorData + this.busquedaact.length;
      if (this.valueData === 0) {
        this.axios
          .get(this.$GlobalUrl + "/api/getdata")
          .then(res => {
            this.resultado = res.data.datos;
            if (this.resultado.length > 0) {
              this.busquedaact = this.resultado.slice(0, 100);
              this.valueData = 100;
              this.getResultados();
            }
          })
          .catch(e => console.error(e));
      } else {
        // console.log(this.valorData, this.resultado.length);
        if (this.valorData === this.resultado.length) {
          document.querySelector("#actualizar").classList.remove("disabled");

          alert("Proceso terminado");
        } else {
          this.busquedaact = this.resultado.slice(
            this.valueData,
            this.valueData + 100
          );
          this.valueData += 100;
          this.getResultados();
        }
      }
    },
    async getResultados() {
      this.valor = 0;
      for (let sku of this.busquedaact) {
        await this.axios
          .get(this.$GlobalUrl + "/api/getbusqueda/" + sku)
          .then(result => {
            //console.log(result.data, this.valor++, sku)
            this.valor++;
          })
          .catch(e => {
            this.valor++;

            //console.log(e, sku, this.valor++)
          });
      }
      this.actualizar();
    }
  }
};
</script>

<style></style>
