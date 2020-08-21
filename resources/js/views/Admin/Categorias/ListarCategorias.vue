<template>
    <div>
        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Categoria</h4>
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
                        ¿Desea Eliminar esta categoria?
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="deleteCategory()"
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
            <template v-slot:cell(status)="row">
                <div>
                    <span v-if="row.item.status === 'up'">Activado</span>
                    <span v-else>Desactivado</span>
                </div>
            </template>
            <template v-slot:cell(editar)="row">
                <router-link
                    :to="{
                        name: 'admin',
                        params: {
                            opcion: 'actualizarcategoria',
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
            @input="getcCategories(currentPage)"
        ></b-pagination>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
            items: [],
            busqueda: "",
            currentPage: 1,
            total: 0,
            to: 0,
            fields: [
                { key: "index", label: "#", isRowHeader: true },
                {
                    key: "name",
                    label: "Nombre"
                },
                { key: "slug", label: "Ruta" },
                { key: "status", label: "Estatus" },
                { key: "editar", label: "Editar" },
                { key: "eliminar", label: "Eliminar" }
            ]
        };
    },
    mounted() {
        this.getCategory();
        //product?page=1"
    },
    watch: {},
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        modal(id) {
            this.id = id;
        },
        buscar() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(
                    this.$GlobalUrl + "/api/category/busqueda/" + this.busqueda,
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
        getCategory() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/category", {
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
                    //console.log(response.data);
                    //this.items = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getcCategories(index) {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/category?page=" + index, {
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
        deleteCategory() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .delete(this.$GlobalUrl + "/api/category/" + this.id, {
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
            this.getcCategories(this.currentPage);
        }
    }
};
</script>
<style></style>
