<template>
  <section class="bg-gris" style="padding:100px 0;">
    <h3 style="color:white; text-align: center;" class="f-500">Las mejores marcas</h3>
    <div class="container" style="padding-top:50px">
      <!-- <b-row>
        <b-col v-for="(provedor, index) in provedores" :key="index">
          <img :src="'/img/provedores/' + provedor" alt class="img-fluid" />
        </b-col>
      </b-row>-->

      <carousel
        :autoplay="true"
        :items="3"
        :center="true"
        :loop="true"
        :responsive="{
                    0: {
                        items: 3,
                        dots: false,
                        autoplay: true
                    },
                    600: {
                        items: 3,
                        dots: false,
                        autoplay: true
                    },
                    1000: {
                        items: 3,
                        dots: false,
                        autoplay: true
                    },
                    1024: {
                        items: 5,
                        dots: false,
                        autoplay: false
                    }
                }"
        :nav="false"
      >
        <img
          v-for="(provedor,index) in provedores"
          :src="'/img/provedores/' + provedor"
          alt="provedor"
          class="img-fluid img-slide"
          :key="index"
          style="width:100%;"
        />
      </carousel>
    </div>
  </section>
</template>

<script>
import carousel from "vue-owl-carousel";

export default {
  data() {
    return {
      provedores: [
        "casio.png",
        "gibson.png",
        "ibanez.png",
        "payner.png",
        "pearl.png",
        "shure.png",
        "zindajan.png"
      ]
    };
  },
  components: {
    carousel
  }
};
</script>

<style>
.img-slide {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
}
.owl-item {
  padding: 0px 17px;
}
</style>
