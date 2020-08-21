<template>
  <div class="container">
    <p style="padding-left:30px;">
      Por el momento Nuestro sistema de pagos esta siendo desarrollado.
      <br />puedes enviarnos tu pedido y mandarnos un whatsapp para
      corroborar.
    </p>
    <form action>
      <div class="row margen">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <p style="text-align:right;">$ {{ total(getNumero(getConfig.envio)) }}</p>
          <div class="row">
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="cardName" class="card-input__label">Metodo de Pago</label>
                <select v-model="tipoPago" class="card-input__input -select">
                  <option value="0">Paypal</option>
                  <option value="1" selected>Banco(SPEI)</option>
                </select>
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="cardName" class="card-input__label">Nombre</label>
                <input
                  type="text"
                  id="cardName"
                  class="card-input__input"
                  v-model="cardName"
                  data-ref="cardName"
                  autocomplete="off"
                />
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="cardLastName" class="card-input__label">Apellidos</label>
                <input
                  type="text"
                  id="cardLastName"
                  class="card-input__input"
                  v-model="cardLastName"
                />
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="direccion" class="card-input__label">Correo</label>
                <input type="email" name="correo" class="card-input__input" v-model="email" />
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="telefono" class="card-input__label">Telefono</label>
                <input type="text" name="telefono" class="card-input__input" v-model="telefono" />
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="direccion" class="card-input__label">Dirección</label>
                <input type="text" name="direccion" class="card-input__input" v-model="direccion" />
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="cp" class="card-input__label">Código Postal</label>
                <div class="d-flex">
                  <input type="text" name="cp" class="card-input__input" v-model="cp" />
                  <a
                    href="javascript:void()"
                    class="btn btn-outline-info"
                    style="margin-left:15px;"
                    @click="getCPInfo"
                  >
                    <i class="fas fa-search"></i>
                  </a>
                </div>
                <!-- <select
                                    class="card-input__input -select"
                                    name
                                    id
                                    v-model="cp"
                                >
                                    <option
                                        v-for="(zip, index) in zips"
                                        :key="'zip' + index"
                                        >{{ zip }}</option
                                    >
                </select>-->
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
              <div class="card-input">
                <label for="direccion" class="card-input__label">Referencias</label>
                <textarea name id cols="30" rows="5" class="form-control" v-model="referencias"></textarea>
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="estado" class="card-input__label">Estado</label>

                <select
                  class="card-input__input -select"
                  name
                  id
                  v-model="estado"
                  @change="getMunicipio()"
                >
                  <option
                    v-for="(stado, index) in estados"
                    :key="'estado' + index"
                    :selected="stado === estado"
                  >{{ stado }}</option>
                </select>
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="direccion" class="card-input__label">Municipio</label>
                <select
                  class="card-input__input -select"
                  name
                  id
                  v-model="municipio"
                  @change="getColonia()"
                >
                  <option
                    v-for="(mun, index) in municipios"
                    :key="'mun' + index"
                    :selected="mun === municipio"
                  >{{ mun }}</option>
                </select>
              </div>
            </b-col>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="card-input">
                <label for="direccion" class="card-input__label">Colonia</label>
                <select class="card-input__input -select" name id v-model="colonia">
                  <option
                    v-for="(col, index) in colonias"
                    :key="'col' + index"
                    :selected="col === colonia"
                  >{{ col }}</option>
                </select>
              </div>
            </b-col>
          </div>
        </div>
      </div>
      <br />
      <button
        type="button"
        @click="pagar"
        :class="['btn', 'btn-success', getdisable]"
        v-if="tipoPago === '1'"
      >Solicitar</button>

      <b-modal v-model="modalShow">
        <div v-if="numeroPedido.length === 0">
          <p>Generando pedido</p>
        </div>
        <div v-else>
          <p>Gracias por realizar su pedidos</p>
          <p>su numero de folio es :{{ numeroPedido }}</p>
        </div>
      </b-modal>
    </form>
    <div v-if="!pagado">
      <PayPal
        v-if="tipoPago === '0'"
        :amount="total(getNumero(getConfig.envio))"
        currency="MXN"
        :client="{
                    sandbox: getConfig.paypal_sandbox,
                    production: getConfig.paypal_production
                }"
        :class="getdisable"
        :env="getConfig.paypal_env"
        @payment-authorized="autorizado"
        @payment-completed="completado"
        @payment-cancelled="cancelado"
      ></PayPal>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import PayPal from "vue-paypal-checkout";

export default {
  data() {
    return {
      currentCardBackground: Math.floor(Math.random() * 25 + 1), // just for fun :D
      cardName: "",
      tipoPago: "1",
      cardLastName: "",
      cardNumber: "",
      cardMonth: "",
      cardYear: "",
      cardCvv: "",
      minCardYear: new Date().getFullYear(),
      amexCardMask: "#### ###### #####",
      otherCardMask: "#### #### #### ####",
      cardNumberTemp: "",
      isCardFlipped: false,
      focusElementStyle: null,
      isInputFocused: false,
      estado: "",
      municipio: "",
      colonia: "",
      cp: "",
      direccion: "",
      telefono: "",
      email: "",
      estados: [],
      municipios: [],
      colonias: [],
      zips: [],
      openpay_id: "",
      openpay_key: "",
      openpay_sandbox_mode: true,
      deviceSessionId: "",
      token: "",
      disable: "",
      modalShow: false,
      numeroPedido: "",
      referencias: "",
      pagado: false,
      credentials: {
        sandbox: "",
        production: ""
      }
    };
  },
  created() {},
  mounted() {
    this.getEstados();
    if (this.$session.has("user")) {
      let user = JSON.parse(this.$session.get("user"));

      this.axios
        .get(this.$GlobalUrl + "/api/auth/user", {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          let usr = response.data;
          this.cardName = usr.firstname;
          this.cardLastName = usr.secondname + " " + usr.lastname;
          this.email = usr.email;
          this.telefono = usr.phone;
        })
        .catch(error => {
          console.log(error.response.data);
        });
    }
  },
  computed: {
    ...mapGetters(["total", "getProductInCart", "getConfig"]),
    getCardAllName() {
      return this.cardName + " " + this.cardLastName;
    },
    getdisable() {
      return this.disable;
    }
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "removeAll"]),
    getNumero(val) {
      return Number(val);
    },
    completado(res, planName) {
      this.postPagar(res.id);
      this.modalShow = true;
      //this.pagar(res.id);
    },
    autorizado(res) {
      //console.log("autorizado", res);
    },
    cancelado(res) {
      //console.log("cancelado", res);
    },
    pagar() {
      switch (this.tipoPago) {
        case "0": {
          break;
        }
        case "1": {
          this.postPagar("");
          break;
        }
      }
    },
    postPagar(folio) {
      this.modalShow = true;
      this.disable = "disabled";
      var metodoPago = parseInt(this.tipoPago);
      //var metodoPago = parseInt("1");
      this.axios
        .post(this.$GlobalUrl + "/api/charge", {
          firstname: this.cardName.toUpperCase(),
          secondname: this.cardLastName.toUpperCase(),
          email: this.email,
          address: this.direccion,
          city: this.municipio,
          state: this.estado,
          zip: this.cp,
          col: this.colonia,
          mount: this.total(this.getNumero(this.getConfig.envio)).replace(
            ",",
            ""
          ),
          metodo: metodoPago,
          phone: this.telefono,
          folio: folio,
          referencias: this.referencias,
          products: JSON.stringify(this.getProductInCart)
        })
        .then(response => {
          this.numeroPedido = response.data.id;
          this.removeAll();
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },
    getCPInfo() {
      let getUrl = "https://api-sepomex.hckdrk.mx/query/info_cp/" + this.cp;

      this.axios
        .get(getUrl)
        .then(response => {
          //console.log(response.data);

          this.estado = response.data[0].response.estado;
          this.municipio = response.data[0].response.municipio;
          this.getColoniaCP();
          this.getEstados();
          this.getMunicipio();
          this.getColonia();
        })
        .catch(error => {
          console.log(error.response.data.error_message);
          this.municipios = [];
          this.municipio = "";
          this.colonias = [];
          this.colonia = "";
          this.estado = "";
          this.getEstados();
        });
    },

    getColoniaCP() {
      let getUrl =
        "https://api-sepomex.hckdrk.mx/query/get_colonia_por_cp/" + this.cp;

      this.axios
        .get(getUrl)
        .then(response => {
          if ((response.data.response.colonia.length = 1)) {
            this.colonia = response.data.response.colonia[0];
          }
        })
        .catch(error => {});
    },
    getEstados() {
      let getUrl = "https://api-sepomex.hckdrk.mx/query/get_estados";
      this.estados = [];
      this.axios
        .get(getUrl)
        .then(response => {
          //console.log(response.data.response.estado);
          this.estados = response.data.response.estado;
          this.estados.sort();
        })
        .catch(error => {
          console.log(error);
        });
    },
    getMunicipio() {
      let getUrl =
        "https://api-sepomex.hckdrk.mx/query/get_municipio_por_estado/" +
        this.estado;
      this.municipios = [];
      this.axios
        .get(getUrl)
        .then(response => {
          this.municipios = response.data.response.municipios;
          this.municipios.sort();
        })
        .catch(error => {});
    },
    getColonia() {
      let getUrl =
        "https://api-sepomex.hckdrk.mx/query/get_colonia_por_municipio/" +
        this.municipio;
      this.colonias = [];
      this.axios
        .get(getUrl)
        .then(response => {
          this.colonias = response.data.response.colonia;
          this.colonias.sort();
        })
        .catch(error => {});

      getUrl =
        "https://api-sepomex.hckdrk.mx/query/get_cp_por_municipio/" +
        this.municipio;
      this.colonias = [];
      this.axios
        .get(getUrl)
        .then(response => {
          this.zips = response.data.response.cp;
          this.colonias.sort();
        })
        .catch(error => {});
    }
  },
  components: {
    PayPal
  }
};
</script>
<style lang="scss">
@import url("https://fonts.googleapis.com/css?family=Source+Code+Pro:400,500,600,700|Source+Sans+Pro:400,600,700&display=swap");

.tarjeta {
  height: 300px;
}
.card-item {
  max-width: 430px;
  height: 270px;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  z-index: 2;
  width: 100%;

  @media screen and (max-width: 480px) {
    max-width: 310px;
    height: 220px;
    width: 90%;
  }

  @media screen and (max-width: 360px) {
    height: 180px;
  }

  &.-active {
    .card-item__side {
      &.-front {
        transform: perspective(1000px) rotateY(180deg) rotateX(0deg)
          rotateZ(0deg);
      }
      &.-back {
        transform: perspective(1000px) rotateY(0) rotateX(0deg) rotateZ(0deg);
        /* box-shadow: 0 20px 50px 0 rgba(81, 88, 206, 0.65);*/
      }
    }
  }

  &__focus {
    position: absolute;
    z-index: 3;
    border-radius: 5px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);
    opacity: 0;
    pointer-events: none;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.65);

    &:after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      background: rgb(8, 20, 47);
      height: 100%;
      border-radius: 5px;
      filter: blur(25px);
      opacity: 0.5;
    }

    &.-active {
      opacity: 1;
    }
  }

  &__side {
    border-radius: 15px;
    overflow: hidden;
    /* box-shadow: 3px 13px 30px 0px rgba(11, 19, 41, 0.5);*/
    box-shadow: 0 20px 60px 0 rgba(14, 42, 90, 0.55);
    transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
    transform-style: preserve-3d;
    transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
    backface-visibility: hidden;
    height: 100%;

    &.-back {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      transform: perspective(2000px) rotateY(-180deg) rotateX(0deg) rotate(0deg);
      z-index: 2;
      padding: 0;
      /* background-color: #2364d2;
             background-image: linear-gradient(
               43deg,
               #4158d0 0%,
               #8555c7 46%,
               #2364d2 100%
             );*/
      height: 100%;

      .card-item__cover {
        transform: rotateY(-180deg);
      }
    }
  }
  &__bg {
    max-width: 100%;
    display: block;
    max-height: 100%;
    height: 100%;
    width: 100%;
    object-fit: cover;
  }
  &__cover {
    height: 100%;
    background-color: #1c1d27;
    position: absolute;
    height: 100%;
    background-color: #1c1d27;
    left: 0;
    top: 0;
    width: 100%;
    border-radius: 15px;
    overflow: hidden;
    &:after {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(6, 2, 29, 0.45);
    }
  }

  &__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 40px;
    padding: 0 10px;

    @media screen and (max-width: 480px) {
      margin-bottom: 25px;
    }
    @media screen and (max-width: 360px) {
      margin-bottom: 15px;
    }
  }

  &__chip {
    width: 60px;
    @media screen and (max-width: 480px) {
      width: 50px;
    }
    @media screen and (max-width: 360px) {
      width: 40px;
    }
  }

  &__type {
    height: 45px;
    position: relative;
    display: flex;
    justify-content: flex-end;
    max-width: 100px;
    margin-left: auto;
    width: 100%;

    @media screen and (max-width: 480px) {
      height: 40px;
      max-width: 90px;
    }
    @media screen and (max-width: 360px) {
      height: 30px;
    }
  }

  &__typeImg {
    max-width: 100%;
    object-fit: contain;
    max-height: 100%;
    object-position: top right;
  }

  &__info {
    color: #fff;
    width: 100%;
    max-width: calc(100% - 85px);
    padding: 10px 15px;
    font-weight: 500;
    display: block;

    cursor: pointer;

    @media screen and (max-width: 480px) {
      padding: 10px;
    }
  }

  &__holder {
    opacity: 0.7;
    font-size: 13px;
    margin-bottom: 6px;
    @media screen and (max-width: 480px) {
      font-size: 12px;
      margin-bottom: 5px;
    }
  }

  &__wrapper {
    font-family: "Source Code Pro", monospace;
    padding: 25px 15px;
    position: relative;
    z-index: 4;
    height: 100%;
    text-shadow: 7px 6px 10px rgba(14, 42, 90, 0.8);
    user-select: none;
    @media screen and (max-width: 480px) {
      padding: 20px 10px;
    }
  }

  &__name {
    font-size: 18px;
    line-height: 1;
    white-space: nowrap;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: uppercase;
    @media screen and (max-width: 480px) {
      font-size: 16px;
    }
  }
  &__nameItem {
    display: inline-block;
    min-width: 8px;
    position: relative;
  }

  &__number {
    font-weight: 500;
    line-height: 1;
    color: #fff;
    font-size: 27px;
    margin-bottom: 35px;
    display: inline-block;
    padding: 10px 15px;
    cursor: pointer;

    @media screen and (max-width: 480px) {
      font-size: 21px;
      margin-bottom: 15px;
      padding: 10px 10px;
    }

    @media screen and (max-width: 360px) {
      font-size: 19px;
      margin-bottom: 10px;
      padding: 10px 10px;
    }
  }

  &__numberItem {
    width: 15px;
    display: inline-block;
    &.-active {
      width: 30px;
    }

    @media screen and (max-width: 480px) {
      width: 13px;

      &.-active {
        width: 16px;
      }
    }

    @media screen and (max-width: 360px) {
      width: 12px;

      &.-active {
        width: 8px;
      }
    }
  }

  &__content {
    color: #fff;
    display: flex;
    align-items: flex-start;
  }

  &__date {
    flex-wrap: wrap;
    font-size: 18px;
    margin-left: auto;
    padding: 10px;
    display: inline-flex;
    width: 80px;
    white-space: nowrap;
    flex-shrink: 0;
    cursor: pointer;
    height: 65px;
    @media screen and (max-width: 480px) {
      font-size: 16px;
    }
  }

  &__dateItem {
    position: relative;
    span {
      width: 22px;
      display: inline-block;
    }
  }

  &__dateTitle {
    opacity: 0.7;
    font-size: 13px;
    padding-bottom: 6px;
    width: 100%;

    @media screen and (max-width: 480px) {
      font-size: 12px;
      padding-bottom: 5px;
    }
  }
  &__band {
    background: rgba(0, 0, 19, 0.8);
    width: 100%;
    height: 50px;
    margin-top: 30px;
    position: relative;
    z-index: 2;
    @media screen and (max-width: 480px) {
      margin-top: 20px;
    }
    @media screen and (max-width: 360px) {
      height: 40px;
      margin-top: 10px;
    }
  }

  &__cvv {
    text-align: right;
    position: relative;
    z-index: 2;
    padding: 15px;
    .card-item__type {
      opacity: 0.7;
    }

    @media screen and (max-width: 360px) {
      padding: 10px 15px;
    }
  }
  &__cvvTitle {
    padding-right: 10px;
    font-size: 15px;
    font-weight: 500;
    color: #fff;
    margin-bottom: 5px;
  }
  &__cvvBand {
    height: 45px;
    background: #fff;
    margin-bottom: 30px;
    text-align: right;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
    color: #1a3b5d;
    font-size: 18px;
    border-radius: 4px;
    box-shadow: 0px 10px 20px -7px rgba(32, 56, 117, 0.35);

    @media screen and (max-width: 480px) {
      height: 40px;
      margin-bottom: 20px;
    }

    @media screen and (max-width: 360px) {
      margin-bottom: 15px;
    }
  }
}

.card-list {
  margin-bottom: -130px;

  @media screen and (max-width: 480px) {
    margin-bottom: -120px;
  }
}

.card-input {
  margin-bottom: 20px;
  &__label {
    margin-bottom: 5px;
    font-weight: 500;

    width: 100%;
    display: block;
    user-select: none;
  }
  &__input {
    width: 100%;
    height: 35px;
    border-radius: 5px;
    box-shadow: none;
    border: 1px solid #ced6e0;
    transition: all 0.3s ease-in-out;
    font-size: 18px;
    padding: 5px 15px;
    background: none;
    color: #1a3b5d;
    font-family: "Source Sans Pro", sans-serif;

    &:hover,
    &:focus {
      border-color: #3d9cff;
    }

    &:focus {
      box-shadow: 0px 10px 20px -13px rgba(32, 56, 117, 0.35);
    }
    &.-select {
      -webkit-appearance: none;
      background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUxJREFUeNrM1sEJwkAQBdCsngXPHsQO9O5FS7AAMVYgdqAd2IGCDWgFnryLFQiCZ8EGnJUNimiyM/tnk4HNEAg/8y6ZmMRVqz9eUJvRaSbvutCZ347bXVJy/ZnvTmdJ862Me+hAbZCTs6GHpyUi1tTSvPnqTpoWZPUa7W7ncT3vK4h4zVejy8QzM3WhVUO8ykI6jOxoGA4ig3BLHcNFSCGqGAkig2yqgpEiMsjSfY9LxYQg7L6r0X6wS29YJiYQYecemY+wHrXD1+bklGhpAhBDeu/JfIVGxaAQ9sb8CI+CQSJ+QmJg0Ii/EE2MBiIXooHRQhRCkBhNhBcEhLkwf05ZCG8ICCOpk0MULmvDSY2M8UawIRExLIQIEgHDRoghihgRIgiigBEjgiFATBACAgFgghEwSAAGgoBCBBgYAg5hYKAIFYgHBo6w9RRgAFfy160QuV8NAAAAAElFTkSuQmCC");
      background-size: 12px;
      background-position: 90% center;
      background-repeat: no-repeat;
      padding-right: 30px;
    }
  }
}

.slide-fade-up-enter-active {
  transition: all 0.25s ease-in-out;
  transition-delay: 0.1s;
  position: relative;
}
.slide-fade-up-leave-active {
  transition: all 0.25s ease-in-out;
  position: absolute;
}
.slide-fade-up-enter {
  opacity: 0;
  transform: translateY(15px);
  pointer-events: none;
}
.slide-fade-up-leave-to {
  opacity: 0;
  transform: translateY(-15px);
  pointer-events: none;
}

.slide-fade-right-enter-active {
  transition: all 0.25s ease-in-out;
  transition-delay: 0.1s;
  position: relative;
}
.slide-fade-right-leave-active {
  transition: all 0.25s ease-in-out;
  position: absolute;
}
.slide-fade-right-enter {
  opacity: 0;
  transform: translateX(10px) rotate(45deg);
  pointer-events: none;
}
.slide-fade-right-leave-to {
  opacity: 0;
  transform: translateX(-10px) rotate(45deg);
  pointer-events: none;
}

.github-btn {
  position: absolute;
  right: 40px;
  bottom: 50px;
  text-decoration: none;
  padding: 15px 25px;
  border-radius: 4px;
  box-shadow: 0px 4px 30px -6px rgba(36, 52, 70, 0.65);
  background: #24292e;
  color: #fff;
  font-weight: bold;
  letter-spacing: 1px;
  font-size: 16px;
  text-align: center;
  transition: all 0.3s ease-in-out;

  @media screen and (min-width: 500px) {
    &:hover {
      transform: scale(1.1);
      box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
    }
  }

  @media screen and (max-width: 700px) {
    position: relative;
    bottom: auto;
    right: auto;
    margin-top: 20px;

    &:active {
      transform: scale(1.1);
      box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
    }
  }
}
.reversa {
  @media screen and (max-width: 500px) {
    & {
      display: flex;
      flex-direction: column-reverse;
    }
  }
}
.margen {
  @media screen and (max-width: 500px) {
    & {
      margin-bottom: 150px;
    }
  }
}
iframe {
  position: absolute;
  top: 0px;
}
</style>
