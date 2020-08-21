<template>
    <div class="container">
        <div class="form-group">
            <label for="exampleInputEmail1">Client ID sandbox</label>
            <input
                type="text"
                class="form-control"
                id="exampleInputEmail1"
                aria-describedby="emailHelp"
                v-model="config.paypal_sandbox"
            />
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Client ID production</label>
            <input
                type="text"
                class="form-control"
                id="exampleInputPassword1"
                v-model="config.paypal_production"
            />
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Modo</label>
            <select class="custom-select" v-model="config.paypal_env">
                <option value="sandbox">Pruebas</option>
                <option value="production">Produccion</option>
            </select>
        </div>

        <button @click="update" class="btn btn-outline-rosa">Guardar</button>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
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
                })
                .catch(error => {
                    console.log(error);
                });
        },
        update() {
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
        }
    }
};
</script>

<style></style>
