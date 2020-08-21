<template>
    <div>
        <h3>{{ titulo }}</h3>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3">
                <div class="form">
                    <div class="form-group">
                        <label for>Nombre</label>
                        <input
                            type="text"
                            v-model="categoria.name"
                            class="form-control"
                            placeholder
                        />
                    </div>
                    <div class="form-group">
                        <label for>Slug</label>
                        <input
                            type="text"
                            v-model="categoria.slug"
                            class="form-control"
                            placeholder
                        />
                    </div>
                    <div class="form-group">
                        <label for>Categoria Padre</label>
                        <select
                            class="form-control"
                            v-model="categoria.mainid"
                            id="exampleFormControlSelect1"
                        >
                            <option value="0">default</option>
                            <option
                                v-for="(categoriax, index) in categorias"
                                :key="index"
                                :value="categoriax.id"
                                >{{ categoriax.name }}</option
                            >
                        </select>
                    </div>
                    <div class="form-group">
                        <label for>Estatus</label>
                        <select
                            class="form-control"
                            v-model="categoria.status"
                            id="exampleFormControlSelect1"
                        >
                            <option value="up">Activado</option>
                            <option value="down">Desactivado</option>
                        </select>
                    </div>
                    <button
                        class="btn btn-outline-success"
                        @click="
                            textobutton === 'guardar'
                                ? registro()
                                : actualizar()
                        "
                    >
                        {{ textobutton }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    props: ["id"],
    data() {
        return {
            titulo: "Nueva Categoria",
            textobutton: "Guardar",
            categorias: {},
            categoria: {}
        };
    },
    watch: {
        "$props.id": {
            handler: function(id) {
                if (typeof this.id === "undefined") {
                    this.titulo = "Nueva Categoria";
                    this.textobutton = "guardar";
                    this.categoria = {};
                } else {
                    this.textobutton = "Actualizar";
                    this.titulo = "Actualizar Categoria";
                    this.getCategory();
                }
                this.getAllCategory();
            },
            deep: true,
            immediate: true
        }
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        getAllCategory() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/category/all", {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.categorias = response.data;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },
        getCategory() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/category/" + this.id, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.categoria = response.data;
                })
                .catch(error => {
                    let errors = error.response.data.errors;
                    for (var clave in errors) {
                        if (errors.hasOwnProperty(clave)) {
                            this.addClassToast("alert toasterror");
                            this.showToast(errors[clave]);
                        }
                    }
                });
        },
        actualizar() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .put(
                    this.$GlobalUrl + "/api/category/" + this.id,
                    this.categoria,
                    {
                        headers: {
                            Authorization: user.token_type + " " + user.token
                        }
                    }
                )
                .then(response => {
                    //console.log(response.data);
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.usuario = {};
                    this.$router.back();
                })
                .catch(error => {
                    let errors = error.response.data.errors;
                    for (var clave in errors) {
                        if (errors.hasOwnProperty(clave)) {
                            this.addClassToast("alert toasterror");
                            this.showToast(errors[clave]);
                        }
                    }
                });
        },
        registro() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .post(this.$GlobalUrl + "/api/category", this.categoria, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    //console.log(response.data);
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.categoria = {};
                })
                .catch(error => {
                    let errors = error.response.data.errors;
                    for (var clave in errors) {
                        if (errors.hasOwnProperty(clave)) {
                            this.addClassToast("alert toasterror");
                        }
                    }
                });
        }
    }
};
</script>

<style></style>
