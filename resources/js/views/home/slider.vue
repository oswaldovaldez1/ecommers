<template>
    <div
        v-if="articulos.length > 0"
        style="position:relative; padding-top:30px;"
    >
        <h3 class="f-500" style="text-align: center;">Los mejores productos</h3>
        <carousel
            :autoplay="true"
            :items="3"
            :responsive="{
                0: {
                    items: 1,
                    dots: false
                },
                600: {
                    items: 2,
                    dots: true
                },
                1000: {
                    items: 3,
                    dots: true
                }
            }"
            :nav="false"
            :loop="true"
        >
            <template slot="prev">
                <span class="prev">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </template>

            <div
                v-for="(articulo, index) in articulos"
                :key="index"
                class="item-slider item-slider5"
            >
                <card v-bind:indice="index" v-bind:articulo="articulo"></card>
            </div>
            <template slot="next">
                <span class="next">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </template>
        </carousel>
    </div>
</template>

<script>
import card from "../Productos/card";
import carousel from "vue-owl-carousel";

export default {
    data() {
        return {
            articulos: []
        };
    },
    components: {
        card,
        carousel
    },
    created() {},
    mounted() {
        this.getdatos();
    },
    methods: {
        getdatos() {
            let url = `${this.$GlobalUrl}/api/getslider`;
            this.axios
                .get(url)
                .then(response => {
                    this.articulos = response.data.datos;
                })
                .catch(error => {});
        }
    }
};
</script>

<style>
.item-slider.item-slider5 {
    padding: 20px;
}

.next,
.prev {
    font-size: 40px;
    position: absolute;
    top: 40%;
    z-index: 99;
}
.next {
    right: -10px;
}
.prev {
    left: -10px;
}
.fas.fa-chevron-left,
.fas.fa-chevron-right {
    color: #18181e;
}
</style>
