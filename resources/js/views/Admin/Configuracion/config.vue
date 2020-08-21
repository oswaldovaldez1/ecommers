<template>
    <div class="container">
        <b-tabs content-class="mt-3">
            <b-tab title="Api" @click="getIndex(0)" active
                ><api v-if="index === 0"></api
            ></b-tab>
            <b-tab title="Paypal" @click="getIndex(1)"
                ><paypal v-if="index === 1"></paypal
            ></b-tab>
            <b-tab title="Mejores Productos" @click="getIndex(2)"
                ><productos v-if="index === 2"></productos
            ></b-tab>
            <b-tab title="Pago y envios" @click="getIndex(3)"
                ><pago v-if="index === 3"></pago
            ></b-tab>
            <b-tab title="Banner Home" @click="getIndex(4)"
                ><banner v-if="index === 4"></banner
            ></b-tab>
        </b-tabs>
    </div>
</template>

<script>
import api from "../../gonher/Actualizar";
import paypal from "./paypal";
import banner from "./banner";
import pago from "./PagoyEnvio";
import productos from "./MejoresProductos";
export default {
    data() {
        return {
            index: 0
        };
    },
    methods: {
        getIndex(i) {
            this.index = i;
        }
    },
    components: {
        api,
        paypal,
        banner,
        pago,
        productos
    }
};
</script>

<style></style>
