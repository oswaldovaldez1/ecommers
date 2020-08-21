<template>
  <div :class="['container', getClass]">
    <span
      v-for="n in 5"
      :key="'nota' + n"
      :id="idbase + n"
      :class="['small', 'material-icons', 'rosa', 'notas', getClass]"
    >music_note</span>
    <!-- <b-row>
            <b-col
                v-for="n in 5"
                :key="'nota' + n"
                cols="2"
                sm="2"
                md="1"
                lg="1"
                xl="1"
            >
                <span
                    :id="idbase + n"
                    :class="[
                        'small',
                        'material-icons',
                        'rosa',
                        'notas',
                        getClass
                    ]"
                    >music_note</span
                >
            </b-col>
    </b-row>-->
  </div>
</template>

<script>
export default {
  props: {
    calificacion: {
      type: Number,
      required: true,
    },
    idbase: {
      type: String,
      required: true,
    },
    clase: {
      type: String,
      required: false,
    },
  },
  computed: {
    getClass() {
      return this.clase;
    },
  },
  watch: {
    "$props.calificacion": {
      handler: function (calificacion) {
        this.notas();
      },
      deep: true,
      immediate: true,
    },
  },
  mounted() {
    this.notas();
  },
  methods: {
    notas() {
      for (let x = 1; x <= this.calificacion; x++) {
        let id = "#" + this.idbase + x;
        document.querySelector(id).classList.add("notasSelect");
      }
    },
  },
};
/*

*/
</script>

<style></style>
