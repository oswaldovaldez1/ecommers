<template>
    <div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                <h3>{{ titulo }}</h3>
                <div class="form">
                    <div class="row">
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Sku</label>
                            <input
                                type="text"
                                v-model="producto.sku"
                                class="form-control"
                                placeholder
                            />
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Title</label>
                            <input
                                type="text"
                                v-model="producto.title"
                                class="form-control"
                                placeholder
                            />
                        </div>

                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Categorias</label>
                            <select
                                class="form-control"
                                v-model="producto.category"
                                id="exampleFormControlSelect1"
                            >
                                <option
                                    v-for="(categoria, index) in categorias"
                                    :key="index"
                                    :value="categoria.id"
                                    >{{ categoria.name }}</option
                                >
                            </select>
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Marca</label>
                            <select
                                class="form-control"
                                v-model="producto.trademark"
                                id="exampleFormControlSelect1"
                            >
                                <option
                                    v-for="(marca, index) in marcas"
                                    :key="index"
                                    :value="marca.nombre"
                                    >{{ marca.nombre }}</option
                                >
                            </select>
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Provedor</label>
                            <select
                                class="form-control"
                                v-model="producto.id_provedor"
                                id="exampleFormControlSelect1"
                            >
                                <option
                                    v-for="(provedor, index) in provedores"
                                    :key="index"
                                    :value="provedor.id"
                                    >{{ provedor.provedor }}</option
                                >
                            </select>
                        </div>

                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Estado</label>
                            <select
                                class="form-control"
                                v-model="producto.status"
                                id="exampleFormControlSelect1"
                            >
                                <option value="0">Desactivado</option>
                                <option value="1">Activado</option>
                            </select>
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Video Youtube</label>
                            <input
                                type="text"
                                v-model="producto.url"
                                class="form-control"
                                placeholder
                            />
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4"
                        >
                            <label for>Precio Base</label>
                            <input
                                type="text"
                                v-model="producto.previus_price"
                                @keypress="onlyNumber"
                                class="form-control"
                                placeholder
                            />
                        </div>
                        <div
                            class="form-group col-12 col-sm-12 col-md-6 col-lg-8"
                        >
                            <label for>Descripcion</label>
                            <ckeditor
                                :editor="editor"
                                v-model="producto.description"
                                :config="editorConfig"
                            ></ckeditor>
                        </div>
                    </div>
                    <button
                        class="btn btn-outline-success subir"
                        id="btnNwPr"
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
            <div class="col-12 col-sm-12 col-md-6">
                <uploaderimg
                    v-if="typeof producto.id != 'undefined'"
                    :id="producto.id"
                />
            </div>
            <div class="col-12 col-sm-12 col-md-12">
                <div class="contenedor-img">
                    <div
                        class="img-cont"
                        v-for="(image, index) in imagenes"
                        :key="index"
                    >
                        <div @click="eliminar(image.id)" class="eliminar">
                            x
                        </div>
                        <img :src="image.url" :alt="`imagen ${index}`" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draganddrop from "./ImgProductos";
import uploaderimg from "./ImageUploader";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { mapActions } from "vuex";
export default {
    props: ["id"],
    data() {
        return {
            titulo: "Nuevo Producto",
            textobutton: "guardar",
            producto: {},
            marcas: {},
            provedores: {},
            categorias: {},
            imagenes: {},
            editor: ClassicEditor,
            editorConfig: {
                toolbar: [
                    "heading",
                    "|",
                    "bold",
                    "italic",
                    "link",
                    "bulletedList",
                    "numberedList",
                    "|",
                    "indent",
                    "outdent",
                    "|",
                    "blockQuote"
                ]
            }
        };
    },
    components: {
        draganddrop,
        uploaderimg
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        eliminar(idImg) {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .delete(
                    this.$GlobalUrl +
                        "/api/product/removeImg/" +
                        idImg +
                        "/" +
                        this.id,
                    {
                        headers: {
                            Authorization: user.token_type + " " + user.token
                        }
                    }
                )
                .then(response => {
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.getImagenes();
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        getImagenes() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/product/getimg/" + this.id, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.imagenes = response.data;
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        getProduct() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/product/" + this.id, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.producto = response.data;
                    //console.log(this.producto, "getprod");
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        getMarcas() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/marca/all", {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    let errors = error.response.data.errors;
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        getProvedores() {
            let user = JSON.parse(this.$session.get("user"));
            this.axios
                .get(this.$GlobalUrl + "/api/provedor/all", {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    this.provedores = response.data;
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        getCategorias() {
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
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        actualizar() {
            let user = JSON.parse(this.$session.get("user"));
            console.log(this.producto);
            this.axios
                .put(
                    this.$GlobalUrl + "/api/product/" + this.id,
                    this.producto,
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
                    //this.producto = {};
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
        onlyNumber($event) {
            //console.log($event.keyCode); //keyCodes value
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                // 46 is dot
                $event.preventDefault();
            }
        },
        registro() {
            //api/user
            let user = JSON.parse(this.$session.get("user"));

            this.axios
                .post(this.$GlobalUrl + "/api/product", this.producto, {
                    headers: {
                        Authorization: user.token_type + " " + user.token
                    }
                })
                .then(response => {
                    //console.log(response.data);
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.producto = response.data.product;
                    this.getImagenes();
                    document
                        .querySelector("#btnNwPr")
                        .classList.add("disabled");
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
    },
    mounted() {},
    watch: {
        "$store.state.toast.show": function() {
            if (this.$store.state.toast.show) {
                this.getImagenes();
            }
        },
        //this.addClassToast("alert toastsuccess");
        "$props.id": {
            handler: function(id) {
                if (typeof this.id === "undefined") {
                    this.producto = {};
                    this.titulo = "Nuevo Producto";
                    this.textobutton = "guardar";
                } else {
                    this.textobutton = "Actualizar";
                    this.titulo = "Actualizar Producto";
                    this.getProduct();
                }
                this.getMarcas();
                this.getProvedores();
                this.getCategorias();
                this.getImagenes();
            },
            deep: true,
            immediate: true
        }
    }
};
</script>

<style lang="scss" scoped>
.contenedor-img {
    width: 100%;
    height: 200px;
    overflow-x: hidden;
    overflow-y: auto;
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;
    .img-cont {
        position: relative;
        width: 160px;
        display: flex;
        flex-direction: column;
        margin: 10px;
        height: 150px;
        justify-content: space-between;
        background: #fff;
        box-shadow: 5px 5px 20px #3e3737;
        img {
            max-height: 105px;
        }
        .eliminar {
            color: #000;
            position: absolute;
            background-color: white;
            height: 23px;
            width: 23px;
            border-radius: 100%;
            font-size: 15px;
            font-weight: 600;
            right: -5px;
            top: -5px;
            cursor: pointer;
            padding-left: 8px;
        }
    }
}
</style>
