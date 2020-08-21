<template>
  <div class="container">
    <div class="row mt-5">
      <div
        :class="['cuadro',valor.class,'pointer']"
        @click="setvista(valor.valor)"
        v-for="(valor,index) in opciones"
        :key="index"
      >
        <span class="pointer" @click="setvista(valor.valor)">{{valor.titulo}}</span>
      </div>
    </div>
    <div class="row">
      <miscompras v-if="vista===1"></miscompras>
      <info v-if="vista===0"></info>
    </div>
  </div>
</template>

<script>
import info from "./updateinfo";
import miscompras from "./miscompras";
export default {
  data() {
    return {
      vista: -1,
      opciones: [
        {
          titulo: "Actualizar información",
          valor: 0,
          class: "br-azul",
        },
        {
          titulo: "Mis compras",
          valor: 1,
          class: "br-rosa",
        },
      ],
    };
  },
  created() {
    if (!this.$session.has("user")) {
      this.$router.push({
        path: `/`,
      });
    }
  },
  components: {
    miscompras,
    info,
  },
  methods: {
    setvista(value) {
      this.vista = value;
    },
  },
};
</script>

<style>
.pointer {
  cursor: pointer;
  text-align: left !important;
}
</style>
