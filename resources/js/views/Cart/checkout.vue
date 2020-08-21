<template>
    <div class="container">
        <div v-if="checkout">
            <div
                v-if="!cartSize"
                class="my-5 alert alert-secondary"
                role="alert"
            >
                Carrito vacio! agregue algún producto.
            </div>
            <div v-else class="table-responsive">
                <table class="table mb-5 table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center">SKU</th>
                            <th scope="col" class="text-center"></th>
                            <th scope="col" class="text-center">Nombre</th>
                            <th scope="col" colspan="3" class="text-center">
                                Cantidad
                            </th>
                            <th scope="col" class="text-right">Total</th>
                            <th scope="col" class="text-center">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(product, index) in cartProducts"
                            :key="product.id"
                        >
                            <th scope="row">{{ index + 1 }}</th>
                            <td>{{ product.id }}</td>
                            <td>
                                <img
                                    class="img-thum"
                                    :src="
                                        getImage(
                                            product.Imagen,
                                            product.ubicacion
                                        )
                                    "
                                    alt="img-product"
                                />
                            </td>
                            <td>{{ product.Nombre }}</td>
                            <td colspan="3">
                                <div>
                                    <button
                                        @click="removeItems(product)"
                                        :disabled="product.cantidad === 1"
                                        class="btn btn-outline-danger btn-small"
                                    >
                                        -
                                    </button>
                                    <span>{{ product.cantidad }}</span>
                                    <button
                                        @click="addItems(product)"
                                        class="btn btn-outline-success btn-small"
                                    >
                                        +
                                    </button>
                                </div>
                            </td>
                            <td class="text-right">
                                {{
                                    getPrecio(product.Precio * product.cantidad)
                                }}
                            </td>
                            <td>
                                <a
                                    href="javascript:void"
                                    @click="remove(index)"
                                    class="btn btn-danger"
                                    >Eliminar</a
                                >
                            </td>
                        </tr>
                        <tr class="table-footer">
                            <td colspan="6" class="total">Gasto de Envío:</td>
                            <td class="text-right">$</td>
                            <td class="text-right">
                                {{ getNumero(getConfig.envio) }}
                            </td>
                        </tr>
                        <tr class="table-footer">
                            <td colspan="6" class="total">Total:</td>
                            <td class="text-right">$</td>
                            <td class="text-right">
                                {{ total(getNumero(getConfig.envio)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <router-link :to="{ name: 'payment' }">
                    <button class="btn btn-success">Pagar</button>
                </router-link>
            </div>
        </div>
        <div v-else class="table-responsive">
            <table class="table mb-5 table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center"></th>
                        <th scope="col" class="text-center">Nombre</th>
                        <th scope="col" class="text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(product, index) in cartProductsWishList"
                        :key="product.id"
                    >
                        <th scope="row">{{ index + 1 }}</th>
                        <!--td>{{ product.id }}</!--td-->
                        <td>
                            <img
                                class="img-thum"
                                :src="
                                    getImage(product.Imagen, product.ubicacion)
                                "
                                alt="img-product"
                            />
                        </td>
                        <router-link
                            :to="{
                                name: 'productinfo',
                                params: { sku: product.id }
                            }"
                        >
                            <td>{{ product.Nombre }}</td>
                        </router-link>
                        <td>
                            <a
                                href="javascript:void"
                                @click="removeWishLists(product)"
                                class="btn btn-danger"
                                >Eliminar</a
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions, mapState } from "vuex";
import Sugar from "sugar";
export default {
    data() {
        return {
            checkout: true,
            costo: 0
        };
    },
    watch: {
        "$route.params.options": {
            handler: function(options) {
                if (options === "checkout") {
                    this.checkout = true;
                } else if (options === "wishlist") {
                    this.checkout = false;
                }
            },
            deep: true,
            immediate: true
        }
    },
    computed: {
        ...mapState(["cartProducts", "cartProductsWishList"]),
        ...mapGetters(["cartSize", "total", "getProductInCart", "getConfig"])
    },
    methods: {
        ...mapActions([
            "removeProduct",
            "addItem",
            "removeItem",
            "removeProductWishList"
        ]),
        remove(index) {
            this.removeProduct(index);
        },
        removeWishLists(product) {
            this.removeProductWishList(product);
        },
        getNumero(val) {
            return Number(val);
        },
        getPrecio(precio) {
            return Sugar.Number.format(precio, 2);
        },
        addItems(product) {
            this.addItem(product);
        },
        removeItems(product) {
            this.removeItem(product);
        },
        getImage(img, ubicacion) {
            if (ubicacion === "local") return img;
            else
                return (
                    "https://" +
                    window.location.host +
                    "/api/gethead/" +
                    img.split("/").join("-")
                );
        }
    }
};
</script>
<style>
.img-thum {
    height: 50px;
    width: auto;
}
</style>
