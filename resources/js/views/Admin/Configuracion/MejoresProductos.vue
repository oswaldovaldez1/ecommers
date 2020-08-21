<template>
    <div class="container">
        <div class=" d-flex d-row">
            <label for="" class="mx-2">SKU</label>
            <input type="text" class="form-control mx-2" name="" id="" />
            <button class="btn btn-outline-rosa mx-2">Agregar</button>
        </div>
        <div>
            <draggable v-model="list" class="drop">
                <div v-for="(item, index) in list" :key="index" class="drag">
                    <div class="closeDiv">
                        <i
                            class="fas fa-times"
                            @click="quitar(index, item.id)"
                        ></i>
                    </div>
                    <img
                        v-if="item.ubicacion === 'api'"
                        :src="setImagen(item.Imagen)"
                        alt=""
                        width="50"
                        class="img-responsive px-2"
                    />

                    <img
                        v-else
                        :src="item.Imagen"
                        alt=""
                        width="50"
                        class="img-responsive px-2"
                    />
                    <p>
                        {{ item.Nombre }}
                    </p>
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
            config: {}
        };
    },
    mounted() {
        this.getConfig();
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
        getConfig() {
            this.axios
                .get(this.$GlobalUrl + "/api/config")
                .then(response => {
                    this.config = response.data[0];
                    this.getdatos();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getdatos() {
            this.list = JSON.parse(this.config.mprod);
        },
        update() {
            this.config.mprod = JSON.stringify(this.list);
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
        },
        setImagen(img) {
            return (
                "http://" +
                window.location.host +
                "/api/gethead/" +
                img.split("/").join("-")
            );
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
.drag {
    width: 300px;
    position: relative;
    display: flex;
    margin: 10px;
    border: 0.5px solid grey;
    cursor: move;
}
.drop {
    display: inline-block;
    vertical-align: top;
    padding: 15px;
    margin-bottom: 20px;
    width: auto;
    height: auto;
}
</style>
