<template>
  <div class="container">
    <b-modal ref="my-modal" hide-footer title="Recuperación de Contraseña">
      <div class="d-block text-center">
        <div class="d-flex flex-row flex-warp justify-content-between align-items-start">
          <label for="seguimiento" class="mx-2 mt-2">Correo</label>
          <input
            type="email"
            name="seguimiento"
            id="seguimiento"
            class="form-control mx-2"
            v-model="registro.email"
            required
          />
          <button
            :class="[
                            'btn',
                            'btn-outline-rosa',
                            'mx-2',
                            registro.email.length > 0 ? '' : 'disabled'
                        ]"
            @click="recuperacionPassword()"
          >Enviar</button>
        </div>
      </div>
      <!-- <div class="d-flex flex-row-reverse flex-warp justify-content-between align-items-start">
        <button
          class="btn btn-outline-danger mt-4"
          @click="hideModal"
          style="max-width:100px;"
        >Cerrar</button>
      </div>-->
    </b-modal>
    <div class="row">
      <div class="col-1 col-sm-1 col-md-4"></div>
      <div class="col-10 col-sm-10 col-md-4 bordeado" v-if="interfaz === 'login'">
        <div style="padding-bottom: 20px;">
          <div class="form-group px-4">
            <label for>Correo</label>
            <input
              type="email"
              class="form-control"
              name
              id
              aria-describedby="emailHelpId"
              placeholder
              v-model="user"
              required
            />
          </div>
          <div class="form-group px-4">
            <label for>Contraseña</label>
            <input
              type="password"
              class="form-control"
              v-on:keyup.enter="login"
              v-model="password"
              name
              id
              placeholder
              required
            />
          </div>
          <button class="btn bt-gris mt-3" @click="login">Iniciar sesión</button>
          <div class="d-flex flex-row justify-content-center mx-4 mt-3">
            <div class="px-2">
              <p style="text-align:center; cursor:pointer;" @click="interfaz = 'registro'">Registro</p>
            </div>
            <div class="px-2">
              <p
                style="text-align:center; cursor:pointer;"
                @click="showModal()"
              >Recuperar Contraseña</p>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-12 col-sm-12 col-md-6">
                        <facebook class="w-100"></facebook>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <google class="w-100"></google>
          </div>-->
        </div>
      </div>
      <div class="col-10 col-sm-10 col-md-4 bordeado" v-if="interfaz === 'registro'">
        <div class="form-group px-4">
          <label for>Nombre</label>
          <input
            type="text"
            class="form-control"
            v-model="registro.firstname"
            name
            id
            placeholder
            required
          />
        </div>
        <div class="form-group px-4">
          <label for>Apellido Paterno</label>
          <input
            type="text"
            class="form-control"
            v-model="registro.secondname"
            name
            id
            placeholder
            required
          />
        </div>
        <div class="form-group px-4">
          <label for>Apellido Materno</label>
          <input type="text" class="form-control" v-model="registro.lastname" name id placeholder />
        </div>
        <div class="form-group px-4">
          <label for>Telefono</label>
          <input type="tel" class="form-control" v-model="registro.phone" name id placeholder />
        </div>
        <div class="form-group px-4">
          <label for>Correo</label>
          <input type="email" class="form-control" v-model="registro.email" name id placeholder />
        </div>
        <div class="form-group px-4">
          <label for>Contraseña</label>
          <input
            type="password"
            class="form-control"
            v-model="registro.password"
            name
            id
            placeholder
          />
        </div>
        <!-- <div class="form-group px-4">
          <input type="hidden" class="form-control" value="2" v-model="registro.rol" />
        </div>-->
        <button class="btn btn-azul-claro" @click="registroUser">Registrarse</button>
        <p class="mt-4" @click="interfaz='login'" style="cursor:pointer;">Iniciar session</p>
      </div>
      <div class="col-10 col-sm-10 col-md-4 bordeado" v-if="interfaz === 'recuperacion'">
        <div class="form-group px-4">
          <label for>Correo</label>
          <input type="email" class="form-control" v-model="registro.email" readonly />
        </div>
        <div class="form-group px-4">
          <label for>Contraseña</label>
          <input
            type="password"
            class="form-control"
            v-model="registro.password"
            name
            id
            placeholder
          />
        </div>
        <button class="btn btn-azul-claro" @click="actualizarPassword">Restaurar</button>
        <p class="mt-4" @click="interfaz='login'" style="cursor:pointer;">Iniciar session</p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import facebook from "./facebook";
import google from "./google";

export default {
  data() {
    return {
      user: "",
      email: "",
      password: "",
      interfaz: "",
      registro: {
        email: "",
      },
    };
  },
  components: {
    google,
    facebook,
  },
  created() {
    //console.log(this.$route.path.indexOf("login"), "waldo");
    if (this.$route.path.indexOf("login") > 0) {
      this.interfaz = "login";
    }
  },
  mounted() {
    if (this.$session.has("user")) {
      this.$router.push({
        name: "admin",
        params: { opcion: "home" },
      });
    }
  },
  watch: {
    "$route.params.token": {
      handler: function (token) {
        if (typeof token === "string") {
          this.recuperacion(token);
          this.interfaz = "recuperacion";
        } else {
          this.interfaz = "login";
        }
      },
      deep: true,
      immediate: true,
    },
    "$route.path": {
      handler: function (path) {
        if (path === "/login") {
          this.interfaz = "login";
        }
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    ...mapActions([
      "addClassToast",
      "showToast",
      "addProduct",
      "showOrHiddenModal",
    ]),
    recuperacion(token) {
      let url = `${this.$GlobalUrl}/api/auth/restore/${token}`;
      this.axios
        .get(url)
        .then((response) => {
          this.registro = response.data;
          this.registro.password = "";
        })
        .catch((error) => {
          this.interfaz = "login";
          this.addClassToast("alert toasterror");
          this.showToast(error.response.data.message);
          this.interfaz = "login";
        });
    },
    registroUser() {
      let url = `${this.$GlobalUrl}/api/auth/signup`;
      this.registro.rol = 2;
      this.axios
        .post(url, this.registro)
        .then((response) => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data);
        })
        .catch((error) => {
          this.addClassToast("alert toasterror");
          this.showToast(error.response.data);
        });
    },
    recuperacionPassword() {
      let url = `${this.$GlobalUrl}/api/auth/restore/${this.registro.email}`;
      this.axios
        .put(url)
        .then((response) => {
          this.addClassToast("alert toastsuccess");
          this.showToast(
            "se ha enviado un correo para recuperar si contraseña"
          );
          this.hideModal();
        })
        .catch((error) => {
          this.addClassToast("alert toasterror");
          this.showToast(error.response.data);
        });
    },
    actualizarPassword() {
      let url = `${this.$GlobalUrl}/api/auth/update/${this.registro.email}`;
      this.axios
        .put(url, this.registro)
        .then((response) => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
        })
        .catch((error) => {
          this.addClassToast("alert toasterror");
          this.showToast(error.response.data.message);
        });
    },
    login() {
      let url = `${this.$GlobalUrl}/api/auth/login`;

      this.axios
        .post(url, {
          email: this.user,
          password: this.password,
        })
        .then((response) => {
          this.$session.set(
            "user",
            JSON.stringify({
              id: response.data.id,
              email: this.user,
              name: response.data.name,
              token: response.data.access_token,
              token_type: response.data.token_type,
              user_type: response.data.user_type,
              expires: response.data.expires_at,
            })
          );

          if (response.data.user_type === "Comprador") {
            this.$router.push({
              name: "home",
            });
          } else {
            this.$router.push({
              name: "admin",
              params: { opcion: "home" },
            });
          }
          //   this.$router.push("/user_main_page");
        })
        .catch((error) => {});
    },
    showModal() {
      this.$refs["my-modal"].show();
    },
    hideModal() {
      this.$refs["my-modal"].hide();
    },
    toggleModal() {
      // We pass the ID of the button that we want to return focus to
      // when the modal has hidden
      this.$refs["my-modal"].toggle("#toggle-btn");
    },
  },
};
</script>

<style>
.bordeado {
  background-color: #18181e1e;
  border-radius: 1.2rem;
  padding-top: 30px;
  padding-bottom: 30px;
  margin-top: 30px;
  text-align: center;
}
</style>
