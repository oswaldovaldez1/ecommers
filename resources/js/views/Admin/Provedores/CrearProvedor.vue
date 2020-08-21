<template>
  <div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
      <div class="col-12 col-sm-12 col-md-8 col-lg-8">
        <h3>{{ titulo }}</h3>
        <div class="form row">
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Nombre</label>
            <input type="text" v-model="provedor.provedor" class="form-control" placeholder />
          </div>
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Porcentaje de ganancia</label>
            <input type="text" v-model="provedor.porcentaje" class="form-control" placeholder />
          </div>
          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
            <label for>Porcentaje de descuento</label>
            <input type="text" v-model="provedor.descuento" class="form-control" placeholder />
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
      titulo: "Nuevo Provedor",
      textobutton: "guardar",
      provedor: {}
    };
  },
  watch: {
    "$props.id": {
      handler: function(id) {
        if (typeof this.id === "undefined") {
          this.titulo = "Nuevo Provedor";
          this.textobutton = "guardar";
          this.provedor = {};
        } else {
          this.textobutton = "Actualizar";
          this.titulo = "Actualizar Provedor";
          this.getProvedor();
        }
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
    getProvedor() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/provedor/" + this.id, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.provedor = response.data;
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
        .put(this.$GlobalUrl + "/api/provedor/" + this.id, this.provedor, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          console.log(response.data);
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.provedor = {};
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
        .post(this.$GlobalUrl + "/api/provedor", this.provedor, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.provedor = {};
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
