<template>
    <div class="container">
        <div>
            <div class="d-flex">
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
            <draggable v-model="list" class="drop">
                <div
                    v-for="(item, index) in list"
                    :key="index"
                    class="drag2 my-2"
                >
                    <div class="closeDiv">
                        <i
                            class="fas fa-times"
                            @click="quitar(index, item.id)"
                        ></i>
                    </div>
                    <img
                        :src="item.url"
                        width="200"
                        alt=""
                        class="img-responsive"
                    />
                </div>
            </draggable>
        </div>
        <button type="button" @click="update()" class="btn btn-outline-rosa">
            Guardar
        </button>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import draggable from "vuedraggable";
export default {
    data() {
        return {
            list: [],
            config: {},
            files: []
        };
    },
    mounted() {
        this.getConfig();
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        previewFiles(e) {
            const files = e.target.files;
            Array.from(files).forEach(file => this.addImage(file));
            this.upload();
            this.files = [];
        },
        addImage(file) {
            if (!file.type.match("image.*")) {
                this.$toastr.e(`${file.name} is not an image`);
                return;
            }
            this.files.push(file);
        },
        getConfig() {
            this.axios
                .get(this.$GlobalUrl + "/api/config")
                .then(response => {
                    this.config = response.data[0];
                    this.list = JSON.parse(this.config.slider);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        async upload() {
            const formData = new FormData();

            this.files.forEach(file => {
                formData.append("name[]", file, file.name);
            });
            await this.axios
                .post("/api/config", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .then(response => {
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.list.push({
                        url: "/assets/storage/banner/" + response.data.name
                    });
                    this.update();
                })
                .catch(error => {
                    this.addClassToast("alert toasterror");
                    this.showToast(error.response.data);
                });
        },
        update() {
            this.config.slider = JSON.stringify(this.list);
            this.axios
                .put(this.$GlobalUrl + "/api/config/1", this.config)
                .then(response => {
                    this.addClassToast("alert toastsuccess");
                    this.showToast(response.data.message);
                    this.getConfig();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        quitar(index, id) {
            this.list.splice(index, 1);
        }
    },
    components: {
        draggable
    }
};
</script>

<style>
.drop {
    display: inline-block;
    vertical-align: top;
    padding: 15px;
    margin-bottom: 20px;
    width: auto;
    height: auto;
}
.drag2 {
    position: relative !important;
    width: 200px;
}
.closeDiv {
    background-color: red;
    width: 20px;
    height: 20px;
    position: absolute;
    right: -10px;
    top: -10px;
    z-index: 99;
    border-radius: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid black;
}

.closeDiv i {
    color: white;
}
</style>
