<template>
  <div>
    <div>
      <banner></banner>
    </div>
    <div class="container" style="margin-top:50px">
      <slider></slider>
    </div>
    <provedores></provedores>
    <vmap></vmap>
  </div>
</template>

<script>
import banner from "./banner";
import slider from "./slider";
import provedores from "./provedores";
import vmap from "./vmap";

export default {
  data() {
    return {};
  },
  components: {
    slider,
    banner,
    vmap,
    provedores
  }
};
</script>
<style>
.img-banner {
  width: 100%;
  height: auto;
}
.tns-nav {
  position: absolute;
  z-index: 0;
}
button[data-action="stop"] {
  position: absolute;
  z-index: 0;
}
</style>
