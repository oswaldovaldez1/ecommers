<template>
  <div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
      <div class="col-12 col-sm-12 col-md-8 col-lg-8">
        <h3>{{ titulo }}</h3>
        <div class="form row">
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Nombre</label>
            <input type="text" v-model="marca.nombre" class="form-control" placeholder />
          </div>
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Tag</label>
            <input type="text" v-model="marca.tag" class="form-control" placeholder />
          </div>
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Porcentaje de Descuento</label>
            <input type="text" v-model="marca.porcentaje" class="form-control" placeholder />
          </div>
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Provedor</label>
            <select class="form-control" v-model="marca.provedores" id="exampleFormControlSelect1">
              <option
                v-for="(provedor, index) in provedores"
                :key="index"
                :value="provedor.id"
              >{{ provedor.provedor }}</option>
            </select>
          </div>
        </div>
        <button
          class="btn btn-outline-success"
          @click="
                            textobutton === 'guardar'
                                ? registro()
                                : actualizar()
                        "
        >{{ textobutton }}</button>
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
      titulo: "Nueva Marca",
      textobutton: "guardar",
      marca: {},
      provedores: {}
    };
  },
  watch: {
    "$props.id": {
      handler: function(id) {
        if (typeof this.id === "undefined") {
          this.titulo = "Nueva Marca";
          this.textobutton = "guardar";
          this.marca = {};
        } else {
          this.textobutton = "Actualizar";
          this.titulo = "Actualizar Marca";
          this.getMarca();
        }
        this.getProvedores();
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
    getMarca() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/marca/" + this.id, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.marca = response.data;
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
        .put(this.$GlobalUrl + "/api/marca/" + this.id, this.marca, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          //console.log(response.data);
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.marca = {};
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
        .post(this.$GlobalUrl + "/api/marca", this.marca, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.marca = {};
        })
        .catch(error => {
          let errors = error.response.data.errors;
          for (var clave in errors) {
            if (errors.hasOwnProperty(clave)) {
              this.addClassToast("alert toasterror");
            }
          }
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
    }
  }
};
</script>

<style></style>
