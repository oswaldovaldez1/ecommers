<template>
  <div class="wishlist">
    <i @click="heartClick" :class="getHeart" style="font-size:24px;"></i>
    <!-- <transition name="bounce" mode="out-in">
            <i @click="heartClick" :class="getHeart" v-if="show"></i>
        </transition>

        <transition name="bounce" mode="in-out">
            <i @click="heartClick" :class="getHeart" v-if="!show"></i>
    </transition>-->
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
export default {
  props: ["articulo"],
  data() {
    return {
      heart: ["far fa-heart", "fas fa-heart wishlist-animate"],
      defaultHeart: 0,
      show: true
    };
  },
  methods: {
    ...mapActions(["addProductWishList", "removeProductWishList"]),
    heartClick() {
      this.defaultHeart = this.defaultHeart === 0 ? 1 : 0;
      if (this.defaultHeart === 0) {
        this.removeProductWishLists();
      } else {
        this.addProductWishLists();
      }

      this.show = !this.show;
    },
    addProductWishLists() {
      this.addProductWishList(this.articulo);
    },
    removeProductWishLists() {
      this.removeProductWishList(this.articulo);
    }
  },
  computed: {
    ...mapGetters(["findWishList"]),
    getHeart() {
      return this.heart[this.defaultHeart];
    }
  },
  mounted() {
    if (this.findWishList(this.articulo) != -1) {
      this.defaultHeart = 1;
    }
  }
};
</script>

<style>
.far.fa-heart,
.fas.fa-heart {
  color: red;
}

.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.01s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.5);
  }
  100% {
    transform: scale(1);
  }
}
.wishlist {
  position: relative;
}
.wishlist-animate {
  animation-name: wishlist-ani;
  animation-duration: 0.9s;
}

@keyframes wishlist-ani {
  0% {
    transform: scale(1);
  }
  25% {
    transform: scale(0.5);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}
/* #heart-2 {
    position: absolute;
    left: 0px;
    bottom: 4px;
} */
</style>

