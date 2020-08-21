<template>
    <div>
        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Provedor</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">¿Desea Eliminar al Provedor?</div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-success"
                            @click="deleteProvedor()"
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
            <template v-slot:cell(id_rol)="row">
                <div>
                    <span v-if="row.item.id_rol === 1">Admin</span>
                    <span v-if="row.item.id_rol === 2">users</span>
                </div>
            </template>
            <template v-slot:cell(editar)="row">
                <router-link
                    :to="{
                        name: 'admin',
                        params: {
                            opcion: 'actualizarprovedor',
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
            @input="getMarcas(currentPage)"
        ></b-pagination>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
            busqueda: "",
            items: [],
            currentPage: 1,
            total: 0,
            to: 0,
            id: -1,
            fields: [
                { key: "index", label: "#", isRowHeader: true },
                {
                    key: "provedor",
                    label: "Provedor"
                },
                { key: "porcentaje", label: "Procentaje" },
                { key: "editar", label: "Editar" },
                { key: "eliminar", label: "Eliminar" }
            ]
        };
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        modal(id) {
            this.id = id;
        },
        buscar() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(
                    this.$GlobalUrl + "/api/provedor/busqueda/" + this.busqueda,
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
        getProvedor() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/provedor", {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.items = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                    this.to = response.data.to;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getProvedores(index) {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/provedor?page=" + index, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.items = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deleteProvedor() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .delete(this.$GlobalUrl + "/api/provedor/" + this.id, {
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
            this.getProvedor();
        }
    },
    mounted() {
        this.getProvedor();
    }
};
</script>
