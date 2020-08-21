<template>
    <div>
        <div v-if="paginas > 0">
            <div class="container">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="paginas"
                    :per-page="30"
                    align="right"
                ></b-pagination>
            </div>
            <b-container>
                <b-row>
                    <b-col
                        cols="12"
                        sm="12"
                        md="6"
                        lg="4"
                        xl="3"
                        v-for="(articulo, index) in articulos"
                        :key="index"
                        class="columnas"
                    >
                        <card
                            v-bind:indice="index"
                            v-bind:articulo="articulo"
                        ></card>
                    </b-col>
                </b-row>
            </b-container>
            <div class="container">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="paginas"
                    :per-page="30"
                    align="right"
                ></b-pagination>
            </div>
        </div>
        <div v-if="completado">
            <div class="container">
                <h5>No se encontro ningun elemento en la busqueda</h5>
            </div>
        </div>
    </div>
</template>
<script>
import card from "./card";
import { mapGetters, mapActions, mapState } from "vuex";
export default {
    data() {
        return {
            articulos: [],
            paginas: 0,
            currentPage: 1,
            completado: false,
            busquedaact: "",
            tipo: ""
        };
    },
    watch: {
        "$route.params.busqueda": {
            handler: function(busqueda) {
                if (typeof busqueda === "string") {
                    this.busquedaact = busqueda;
                    this.currentPage = 1;
                    this.tipo = "busqueda";
                    this.iniciando();
                }
            },
            deep: true,
            immediate: true
        },
        "$route.params.category": {
            handler: function(category) {
                if (typeof category === "string") {
                    this.busquedaact = category;
                    this.currentPage = 1;
                    this.tipo = "categoria";
                    this.iniciando();
                }
            },
            deep: true,
            immediate: true
        },
        currentPage(newPage) {
            this.detlleArticulo();
        }
    },
    methods: {
        ...mapActions(["addProducts", "addSearch", "addPage", "addPaginas"]),
        iniciando() {
            if (
                this.getSearch != this.busquedaact ||
                this.getTotalProducts === 0
            ) {
                this.addSearch(this.busquedaact);
                this.articulos = [];
                let url = "";
                if (this.tipo === "categoria") {
                    url = `${this.$GlobalUrl}/api/getcategory/${this.busquedaact}/${this.currentPage}`;
                } else {
                    url = `${this.$GlobalUrl}/api/searchproduct/${this.busquedaact}/${this.currentPage}`;
                }
                this.axios
                    .get(url)
                    .then(response => {
                        this.articulos = response.data.datos;
                        //console.log(response.data);
                        this.paginas = response.data.total;
                        this.addProducts(this.articulos);
                        this.addSearch(this.busquedaact);
                        this.addPage(this.currentPage);
                        this.addPaginas(this.paginas);
                    })
                    .catch(error => {});
            } else {
                this.articulos = this.getProducts;
                this.paginas = this.getPaginas;
                this.currentPage = this.getCurrentPage;
                this.busquedaact = this.getSearch;
                this.paginas = this.getPaginas;
            }
        },
        detlleArticulo() {
            let url = "";
            if (this.tipo === "categoria") {
                url = `${this.$GlobalUrl}/api/getcategory/${this.busquedaact}/${this.currentPage}`;
            } else {
                url = `${this.$GlobalUrl}/api/searchproduct/${this.busquedaact}/${this.currentPage}`;
            }

            this.axios
                .get(url)
                .then(response => {
                    this.articulos = response.data.datos;
                    this.addProducts(this.articulos);
                    this.addSearch(this.busquedaact);
                    this.addPage(this.currentPage);
                })
                .catch(error => {});
        }
    },
    computed: {
        ...mapGetters([
            "getProducts",
            "getSearch",
            "getCurrentPage",
            "getPaginas",
            "getTotalProducts"
        ])
    },
    components: {
        card
    }
};
</script>
