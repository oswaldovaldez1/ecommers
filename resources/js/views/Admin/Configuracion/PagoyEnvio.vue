<template>
    <div class="container">
        <b-row>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Beneficiario</label>
                    <input
                        type="text"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        v-model="config.beneficiario"
                    />
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Banco</label>
                    <input
                        type="text"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        v-model="config.banco"
                    />
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Clabe</label>
                    <input
                        type="text"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        v-model="config.clabe"
                    />
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Referencia</label>
                    <input
                        type="text"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        v-model="config.referencia"
                    />
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Concepto del pago</label>
                    <input
                        type="text"
                        class="form-control"
                        id="exampleInputEmail1"
                        aria-describedby="emailHelp"
                        v-model="config.concepto"
                    />
                </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
                <div class="form-group">
                    <label for="costo">Costo de Envío</label>
                    <input
                        type="text"
                        class="form-control"
                        id="costo"
                        v-model="config.envio"
                    />
                </div>
            </b-col>
        </b-row>
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
