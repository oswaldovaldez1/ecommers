<template>
  <div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
      <div class="col-12 col-sm-12 col-md-8 col-lg-8">
        <h3>{{ titulo }}</h3>
        <div class="form">
          <div class="row">
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Nombre</label>
              <input type="text" v-model="usuario.firstname" class="form-control" placeholder />
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Apellido Paterno</label>
              <input type="text" v-model="usuario.secondname" class="form-control" placeholder />
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Apellido Materno</label>
              <input type="text" v-model="usuario.lastname" class="form-control" placeholder />
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Correo</label>
              <input
                type="email"
                v-model="usuario.email"
                class="form-control"
                :readonly="
                                    textobutton === 'Actualizar' ? true : false
                                "
                placeholder
              />
            </div>
            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Telefono</label>
              <input type="phone" v-model="usuario.phone" class="form-control" placeholder />
            </div>
            <!-- <div class="form-group col-12 col-sm-12 col-md-6 col-lg-4">
              <label for>Contraseña</label>
              <input type="password" v-model="usuario.password" class="form-control" placeholder />
            </div>-->
          </div>
          <button
            class="btn btn-outline-success"
            @click="
                             actualizar()
                        "
          >{{ textobutton }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      titulo: "Actualizar Información",
      textobutton: "Actualizar",
      usuario: {},
    };
  },
  created() {
    this.getuser();
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
    getuser() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/user/" + user.id, {
          headers: {
            Authorization: user.token_type + " " + user.token,
          },
        })
        .then((response) => {
          this.usuario = response.data;
          this.usuario.password = "";
          this.usuario.rol = this.usuario.tipo;
        })
        .catch((error) => {
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
        .put(this.$GlobalUrl + "/api/user/" + this.id, this.usuario, {
          headers: {
            Authorization: user.token_type + " " + user.token,
          },
        })
        .then((response) => {
          //console.log(response.data);
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.usuario = {};
          this.$router.back();
        })
        .catch((error) => {
          let errors = error.response.data.errors;
          for (var clave in errors) {
            if (errors.hasOwnProperty(clave)) {
              this.addClassToast("alert toasterror");
              this.showToast(errors[clave]);
            }
          }
        });
    },
  },
};
</script>

<style></style>
