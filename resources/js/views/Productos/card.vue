<template>
    <div>
        <div :class="['card', 'bg-blanco']">
            <router-link
                :to="{ name: 'productinfo', params: { sku: articulo.id } }"
            >
                <div
                    class="img-card"
                    v-if="articulo.ubicacion === 'api'"
                    v-bind:style="{
                        'background-image': 'url(' + setImagen + ')'
                    }"
                ></div>
                <div
                    class="img-card"
                    v-else
                    v-bind:style="{
                        'background-image': 'url(' + articulo.Imagen + ')'
                    }"
                ></div>
            </router-link>
            <div class="card-body">
                <div class="wish-item">
                    <wishlist v-bind:articulo="articulo" />
                </div>
                <div :class="['titulo', setBgCard]">
                    <router-link
                        :to="{
                            name: 'productinfo',
                            params: { sku: articulo.id }
                        }"
                    >
                        <p
                            :class="[setTitleClass, setBgCard]"
                            @mouseout="hover = false"
                            @mouseenter="hover = true"
                        >
                            {{ setNombre }}
                        </p>
                    </router-link>
                </div>
                <!-- <div class="contenido">
                    <p class="card-text">{{ setDescripcion }}</p>
        </div>-->
                <div class="precio">
                    <div>
                        <p
                            class="f-500 rosa"
                            style="text-align: center;
    font-size: 20px;"
                        >
                            $ {{ getPrecio }}
                        </p>
                    </div>
                    <b-row>
                        <b-col>
                            <button
                                class="btn btn-outline-rosa btn-lg btn-block"
                                @click="addProductToCart"
                            >
                                <i
                                    class="fa fa-cart-plus"
                                    style="font-size:24px;"
                                    aria-hidden="true"
                                ></i>
                            </button>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
import wishlist from "./wishlist";
import Sugar from "sugar";
export default {
    props: ["indice", "articulo"],
    data() {
        return {
            Imagen: "",
            hover: false
        };
    },
    methods: {
        ...mapActions([
            "addClassToast",
            "showToast",
            "addProduct",
            "showOrHiddenModal",
            "addProductWishLis",
            "removeProductWishList"
        ]),

        addProductToCart() {
            this.addProduct(this.articulo);
            this.addClassToast("alert toastsuccess");
            this.showToast("Producto Agregado");
        },
        openModal() {
            this.showOrHiddenModal();
        }
    },
    components: {
        wishlist
    },
    computed: {
        ...mapState(["cartProducts"]),
        setNombre() {
            return this.articulo.Nombre;
        },
        setTitleClass() {
            //card-title f-400 gris
            return this.hover === false
                ? "card-title f-400 gris"
                : "card-title f-400 blanco";
        },
        setBgCard() {
            return this.hover === false ? "bg-blanco" : "bg-rosa";
        },
        setObject() {
            return {
                id: this.articulo.id,
                cantidad: 0,
                nombre: this.articulo.Nombre,
                img: this.articulo.Imagen,
                precio: 0
            };
        },
        setDescripcion() {
            var Sugar = require("sugar");
            return Sugar.String.truncate(this.articulo.Descripcion, 40);
        },
        setImagen() {

            return (
                "https://" +
                window.location.host +
                "/api/gethead/" +
                this.articulo.Imagen.split("/").join("-")
            );
        },
        getPrecio() {
            return Sugar.Number.format(this.articulo.Precio, 2);
        }
    }
};
</script>
<style>
.card-body {
    padding-left: 0px;
    padding-right: 0px;
}
.titulo,
.precio {
    padding-left: 1.25rem;
    padding-right: 1.25rem;
}
.wish-item {
    border-radius: 100%;
    width: 30px;
    height: 30px;
    position: absolute;
    right: 10px;
    top: -14px;
    background-color: white;
    padding-left: 2px;
    padding-top: 2px;
}
.card-body {
    position: relative;
}
.img-card {
    border-bottom-color: rgba(0, 0, 0, 0.125);
    border-bottom-style: solid;
    border-bottom-width: 1px;
}

.card:hover {
    -webkit-box-shadow: 6px 6px 12px 0px rgba(0, 0, 0, 0.125);
    -moz-box-shadow: 6px 6px 12px 0px rgba(0, 0, 0, 0.125);
    box-shadow: 6px 6px 12px 0px rgba(0, 0, 0, 0.125);
    transform: scale(1.1, 1.1);
}
</style>
