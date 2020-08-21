<template>
  <div :class="classtoast" v-show="isActive">{{text}}</div>
</template>

<script>
export default {
  props: ["show", "text", "classtoast"],
  data() {
    return {
      isActive: !!this.show,
      activeTimeout: {}
    };
  },
  mounted() {
    this.setTimeout();
  },
  watch: {
    show(newVal) {
      this.isActive = !!newVal;
    },
    isActive(newVal) {
      if (this.show !== !!newVal) {
        this.$emit("hide-toast", newVal);
      }
      this.setTimeout();
    }
  },
  methods: {
    setTimeout() {
      clearTimeout(this.activeTimeout);
      if (this.isActive) {
        this.activeTimeout = setTimeout(() => {
          this.isActive = false;
        }, 2000);
      }
    }
  }
};
</script>


<style scoped>
.alert {
  width: 30%;
  text-align: center;
  font-weight: bold;
  color: white;
  position: fixed;
  top: 9%;
  right: -12%;
  transform: translateX(-50%);
  z-index: 999;
}
.toastdefault {
  background-color: rgba(0, 0, 0, 0.7);
}
.toasterror {
  background-color: red;
}
.toastsuccess {
  background: green;
}
</style>
