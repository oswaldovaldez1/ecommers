<template>
    <div>
        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Producto</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        ¿Desea Eliminar a este producto?
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="deleteproduct()"
                            data-dismiss="modal"
                        >
                            Eliminar
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-dismiss="modal"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <b-row>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
                <div class="busqueda">
                    <input
                        class="form-control"
                        type="text"
                        name=""
                        id=""
                        placeholder="Buscar"
                        v-model="busqueda"
                        v-on:keyup.enter="buscar"
                    />
                    <button class="btn btn-outline-info" @click="buscar">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
                <div class="busqueda w-100">
                    <div class="custom-file">
                        <input
                            type="file"
                            class="custom-file-input"
                            id="customFileLang"
                            lang="es"
                            @change="previewFiles"
                        />
                        <label class="custom-file-label" for="customFileLang"
                            >Seleccionar Archivo</label
                        >
                    </div>
                </div>
                <!-- <input
                    type="file"
                    id="input"
                    class="btn btn-outline-rosa"
                    @change="previewFiles"
                /> -->
            </b-col>
            <b-col cols="12" sm="12" md="12" lg="12" xl="12">
                <b-progress
                    :value="valueData"
                    :max="archivos.length"
                    show-value
                    class="mb-3 w-50"
                    show-progress
                    animated
                    v-if="archivos.length > 0"
                ></b-progress>
            </b-col>
            <b-col
                cols="12"
                sm="12"
                md="12"
                lg="12"
                xl="12"
                v-if="errorSubir.length > 0"
            >
                <p>
                    existen algunos productos que no se pudieron subir,
                    verifique el siguiente archivo:
                </p>
                <export-excel
                    class="btn btn-outline-danger"
                    :data="productos"
                    :fields="encabezados"
                    worksheet="Productos"
                    name="Productos.xlsx"
                >
                    Descargar
                    <i class="fas fa-download"></i>
                </export-excel>
            </b-col>
        </b-row>

        <b-table
            :items="items"
            :fields="fields"
            striped
            hover
            responsive
            sticky-header
        >
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(description)="row">
                <div
                    style="width: 100%;
    height: 44px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;"
                    v-html="row.item.description"
                ></div>
            </template>
            <template v-slot:cell(editar)="row">
                <router-link
                    :to="{
                        name: 'admin',
                        params: {
                            opcion: 'actualizarproducto',
                            id: row.item.id
                        }
                    }"
                >
                    <button class="btn btn-info">Editar</button>
                </router-link>
            </template>
            <template v-slot:cell(eliminar)="row">
                <button
                    class="btn btn-danger"
                    @click="modal(row.item.id)"
                    data-toggle="modal"
                    data-target="#myModal"
                >
                    Eliminar
                </button>
            </template>
        </b-table>
        <b-pagination
            v-model="currentPage"
            :total-rows="total"
            :per-page="to"
            align="right"
            @input="getproducts(currentPage)"
        ></b-pagination>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import readXlsxFile from "read-excel-file";
import excel from "vue-excel-export";
export default {
    data() {
        return {
            archivos: [],
            productos: [],
            errorSubir: [],
            encabezados: {
                clave: "clave",
                titulo: "titulo",
                descripcion: "descripcion",
                distribuidor: "distribuidor",
                marca: "marca",
                categoria: "categoria",
                provedor: "provedor",
                url: "url"
            },
            busqueda: "",
            valueData: 0,
            items: [],
            id: -1,
            currentPage: 1,
            total: 0,
            to: 0,
            fields: [
                { key: "index", label: "#", isRowHeader: true },
                {
                    key: "sku",
                    label: "SKU"
                },
                { key: "title", label: "Titulo" },
                { key: "description", label: "Descripción" },
                { key: "description", label: "status" },
                { key: "editar", label: "Editar" },
                { key: "eliminar", label: "Eliminar" }
            ]
        };
    },
    mounted() {
        this.getProductos();
        //product?page=1"
    },
    watch: {},
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        previewFiles(event) {
            this.errorSubir = [];
            let files = event.target.files;

            readXlsxFile(files[0]).then(rows => {
                // console.log(rows);
                rows.map((row, index) => {
                    if (index != 0) {
                        //console.log(index);
                        this.archivos.push({
                            sku: row[0],
                            title: row[1],
                            description: row[2],
                            previus_price: row[3],
                            price: 0,
                            category: row[5],
                            trademark: row[4],
                            id_provedor: row[6],
                            url: row[7],
                            status: 1
                        });
                        this.productos.push({
                            clave: row[0],
                            titulo: row[1],
                            descripcion: row[2],
                            distribuidor: row[3],
                            marca: row[4],
                            categoria: row[5],
                            provedor: row[6],
                            url: row[7]
                        });
                    }
                });
                this.subirProductos();
            });
        },
        async subirProductos() {
            let user = JSON.parse(this.$session.get("user"));

            for (let x = 0; x < this.archivos.length; x++) {
                await this.axios
                    .post(
                        this.$GlobalUrl + "/api/postproduct",
                        this.archivos[x],
                        {
                            headers: {
                                Authorization:
                                    user.token_type + " " + user.token
                            }
                        }
                    )
                    .then(response => {
                        // console.log("completo");
                        this.valueData++;
                    })
                    .catch(error => {
                        // console.error("error");
                        this.errorSubir.push(this.productos[x]);
                        this.valueData++;
                    });
                // console.log(x, this.archivos.length);
            }
            this.archivos = [];
        },
        buscar() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(
                    this.$GlobalUrl + "/api/product/busqueda/" + this.busqueda,
                    {
                        headers: {
                            Authorization: user.token_type + " " + user.token
                        }
                    }
                )
                .then(response => {
                    this.items = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getProductos() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/product", {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    //   this.valores = response.data.data;
                    //   this.busqueda = this.valores;
                    this.items = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                    this.to = response.data.to;
                    //this.items = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        modal(index) {
            this.id = index;
        },
        getproducts(index) {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/product?page=" + index, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    //   this.valores = response.data.data;
                    //   this.busqueda = this.valores;
                    this.items = response.data.data;
                    //this.items = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deleteproduct() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .delete(this.$GlobalUrl + "/api/product/" + this.id, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
            this.getproducts(this.currentPage);
        }
    }
};
</script>
<style></style>
