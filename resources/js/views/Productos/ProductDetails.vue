<template>
  <div class="container">
    <div class="row" v-if="typeof product === 'object'">
      <div class="col-12 col sm-12 col-md-12 col-lg-6" style="padding-bottom:50px;">
        <ProductZoomer :base-images="images" :base-zoomer-options="zoomerOptions" />
      </div>
      <div class="col-12 col sm-12 col-md-12 col-lg-6" style="margin-bottom:50px;">
        <b-row>
          <b-col cols="10" sm="10" md="10" lg="10" xl="10">
            <h1 class="f-400" style="font-size:23px; padding-left:20px;">{{ product.Nombre }}</h1>
            <notas :calificacion="cal" idbase="notasview"></notas>
            <span class="vendidos gris">
              Total vendidos:
              <span class="numeroVendidos azul">
                {{
                product.vendido
                }}
              </span>
            </span>
          </b-col>
          <b-col cols="1">
            <h3>
              <wishlist v-bind:articulo="product" />
            </h3>
          </b-col>
        </b-row>
        <div class="row justify-content-between" style="margin-bottom:20px;">
          <div class="col-5" style="display: block;">
            <p
              class="f-500 rosa"
              style="font-size:1.3rem; padding-top:0.5rem; text-align: center;"
            >$ {{ getPrecio }}</p>
          </div>
          <div class="col-4" style="text-align:right">
            <button class="btn btn-outline-rosa btn-lg btn-block" @click="addProductToCart">
              <i class="fa fa-cart-plus" aria-hidden="true" style="font-size:24px"></i>
            </button>
          </div>
        </div>
        <div class="content-desc" style="padding-left:20px" v-html="getDescripcion"></div>
      </div>
    </div>
    <div class="flex-row row d-flex flex-lg-row-reverse">
      <b-col cols="12" sm="12" md="6" lg="6" xl="6">
        <youtube
          v-if="product.url.length > 0"
          :video-id="getId"
          :ref="product.url"
          @playing="playing"
        ></youtube>
      </b-col>
      <b-col cols="12" sm="12" md="6" lg="6" xl="6" v-if="pruebas">
        <div class="row">
          <div class="col-12">
            <div class="d-flex flex-column align-end" style="width:300px">
              <h3 class="align-self-start">Comentarios</h3>
              <br />
              <div class="container" style="margin-top:5px;">
                <span
                  v-for="n in 5"
                  :key="'notax' + n"
                  @click="checkNotas(n)"
                  @mouseover="selectNotas(n)"
                  @mouseleave="unSelectNotas()"
                  :id="'notax' + n"
                  class="small material-icons rosa notas"
                >music_note</span>
              </div>
              <br />
              <input
                type="email"
                name
                id
                placeholder="Correo"
                v-model="mensaje.correo"
                class="form-control form-control-sm"
                required
              />

              <small id="emailHelp" class="form-text text-muted">Campo Obligatorio</small>
              <br />
              <textarea
                placeholder="mensaje"
                v-model="mensaje.comentario"
                class="form-control form-control-sm"
                style="margin-bottom:10px"
                required
              ></textarea>
              <br />
              <br />
              <button @click="addMensaje()" class="btn btn-outline-rosa btn-md">Enviar</button>

              <br />
              <hr class="w-100 gris" />
              <br />
            </div>
          </div>
          <div class="col-12">
            <a
              href="javascript:void()"
              class="azul"
              v-b-toggle.collapse-1
              variant="primary"
            >Ver comentarios</a>

            <b-collapse id="collapse-1" class="mt-2">
              <div class="divmensaje" v-for="(message, index) in mensajes" :key="index">
                <p class="mensaje">{{ message.comentario }}</p>

                <span class="correo">{{ message.fecha }}</span>
                <notas :idbase="makeid(10)" :calificacion="message.cal" clase="ismensaje"></notas>
                <p class="correo">{{ message.correo }}</p>
                <hr class="w-100" />
              </div>
            </b-collapse>
          </div>
        </div>
      </b-col>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
import Sugar from "sugar";
import wishlist from "./wishlist";
import notas from "./notas";

export default {
  data() {
    return {
      product: Object,
      imagenes: [],
      imgcurrent: 0,
      zoomerOptions: {
        zoomFactor: 5,
        pane: "pane",
        hoverDelay: 300,
        namespace: "zoomer-bottom",
        move_by_click: false,
        scroll_items: 3,
        choosed_thumb_border_color: "#ffffff00",
        scroller_position: "bottom",
        zoomer_pane_position: "right",
      },
      mensajes: [],
      mensaje: {},
      cal: 0,
      cal1: 0,
      pruebas: true,
    };
  },
  created() {},
  watch: {
    "$route.params.sku": {
      handler: function (sku) {
        this.detalleProducto(sku);
      },
      deep: true,
      immediate: true,
    },
  },
  components: {
    wishlist,
    notas,
  },
  mounted() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0;

    // console.log(this.videoId, "idvideo");
  },
  methods: {
    ...mapActions([
      "addClassToast",
      "showToast",
      "addProduct",
      "showOrHiddenModal",
    ]),
    getCalificacion() {
      let suma = 0;
      let contador = 0;
      this.mensajes.map((mensaje) => {
        suma += mensaje.cal;
        contador++;
      });
      if (suma === 0) {
        return 0;
      }
      this.cal = Math.round(((suma / 5 / contador) * 100) / 20);
    },
    agregarComentario() {
      this.axios
        .post(this.$GlobalUrl + "/api/comentario", this.mensaje)
        .then((response) => {
          this.mensaje = {};
          this.cal = 0;
          this.cal1 = 0;
          this.unSelectNotas();
          this.addClassToast("alert toastsuccess");
          this.showToast("Comentario Agregado");
          this.listarComentarios();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    listarComentarios() {
      this.axios
        .get("/api/comentario/busqueda/" + this.product.id)
        .then((response) => {
          this.mensajes = response.data;
          this.getCalificacion();
        })
        .catch((error) => {});
    },
    makeid(length) {
      var result = "";
      var characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      var charactersLength = characters.length;
      for (var i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }
      return result;
    },
    validEmail: function (email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
    addMensaje() {
      if (typeof this.mensaje.correo === "undefined") {
        return;
      }
      if (typeof this.mensaje.comentario === "undefined") {
        this.mensaje.comentario = "-";
      }
      if (!this.validEmail(this.mensaje.correo)) {
        return;
      }
      this.mensaje.cal = this.cal1;

      this.mensaje.sku = this.product.id;

      this.agregarComentario();

      //this.mensajes.push(this.mensaje);
    },
    checkNotas(n) {
      this.cal1 = n;

      for (let x = 1; x <= 5; x++) {
        let id = "#notax" + x;
        document.querySelector(id).classList.remove("notasCheck");
      }
      for (let x = 1; x <= n; x++) {
        let id = "#notax" + x;
        document.querySelector(id).classList.add("notasCheck");
      }
    },
    selectNotas(n) {
      for (let x = 1; x <= 5; x++) {
        let id = "#notax" + x;
        document.querySelector(id).classList.remove("notasSelect");
      }
      for (let x = 1; x <= n; x++) {
        let id = "#notax" + x;
        document.querySelector(id).classList.add("notasSelect");
      }
    },
    unSelectNotas() {
      for (let x = 1; x <= 5; x++) {
        let id = "#notax" + x;
        document.querySelector(id).classList.remove("notasSelect");
      }
    },
    playing() {
      // console.log("\o/ we are watching!!!");
    },
    addProductToCart() {
      this.addProduct(this.product);
      this.addClassToast("alert toastsuccess");
      this.showToast("Producto Agregado");
    },
    openModal() {
      this.showOrHiddenModal();
    },
    setImagen(index) {
      this.imgcurrent = index;
    },
    getUrlImage(img) {
      //let urlArray = img.split("/");
      if (this.product.ubicacion === "local") return img;
      else
        return (
          "https://" +
          window.location.host +
          "/api/gethead/" +
          img.split("/").join("-")
        );
    },
    detalleProducto(sku) {
      this.imagenes = [];
      this.product = this.getcurrentProduct(sku);
      if (typeof this.product === "object") {
        if (this.product.ubicacion === "local") {
          this.imagenes.push({
            id: 1,
            url: this.product.Imagen,
          });
          let x = 2;
          this.product.Imgs.map((img) => {
            // console.log(img);
            this.imagenes.push({
              id: "img_" + x,
              url: img,
            });
            x++;
          });
        } else {
          this.imagenes.push({
            id: 1,
            url: this.getUrlImage(this.product.Imagen),
          });
          let x = 2;
          this.product.Imgs.map((img) => {
            // console.log(img);
            this.imagenes.push({
              id: "img_" + x,
              url: this.getUrlImage(img),
            });
            x++;
          });
        }
      }

      if (typeof this.product === "undefined") {
        let url = `${this.$GlobalUrl}/api/getdetalle/${sku}`;

        this.axios
          .get(url)
          .then((response) => {
            this.product = response.data;
            this.listarComentarios();
            if (Array.isArray(this.product)) {
              return this.$router.push("*");
            }
            this.imagenes.push({
              id: 1,
              url: this.getUrlImage(this.product.Imagen),
            });
            let x = 2;
            this.product.Imgs.map((img) => {
              // console.log(img);
              this.imagenes.push({
                id: "img_" + x,
                url: this.getUrlImage(img),
              });
              x++;
            });
          })
          .catch((error) => {});
      }
    },
  },
  computed: {
    ...mapGetters(["getcurrentProduct"]),

    getImg() {
      return this.imagenes[this.imgcurrent];
    },
    getDescripcion() {
      return this.product.Descripcion_completa.length === 0
        ? this.product.Descripcion
        : this.product.Descripcion_completa;
    },
    getPrecio() {
      return Sugar.Number.format(this.product.Precio, 2);
    },
    images() {
      return {
        thumbs: this.imagenes,
        normal_size: this.imagenes,
        large_size: this.imagenes,
      };
    },
    getUrl() {
      return window.location.href;
    },
    getId() {
      return this.$youtube.getIdFromUrl(this.product.url);
    },
  },
};
</script>

<style>
.img-view {
  height: 265px !important;
  width: auto;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}
.img-thum {
  height: 70px;
  width: 60px;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}
.content-desc {
  height: 320px;
  overflow-y: auto;
  padding: 0px 10px;
}
.pane-container {
  max-width: 600px !important;
  max-height: 600px !important;
  min-height: 300px !important;
  top: 0px !important;
  left: 10% !important;
}
.scroller-at-bottom .thumb-list .responsive-image[data-v-033bd07e] {
  width: 40px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
.zoomer-bottom-base-container.scroller-at-bottom,
.thumb-list {
  width: 100% !important;
  height: auto !important;
}
img.preview-box {
  width: auto;
  height: auto !important;
  max-height: 280px;
  max-width: 80% !important;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
iframe[title="YouTube video player"] {
  position: relative;
  width: 100%;
  min-height: 300px;
}
.notas {
  color: white;
  -webkit-text-stroke: 0.5px #18181e;
}
.notas:hover {
  cursor: pointer;
}
.notasCheck {
  color: #ef7968 !important;
  -webkit-text-stroke: 0.5px #335da8;
}
.notasSelect {
  color: #ef7968 !important;
  -webkit-text-stroke: 0.5px #ef7968;
}
.vendidos {
  padding-top: 20px;
  font-size: 12px;
  font-weight: 300;
}
.numeroVendidos {
  font-weight: 800;
}
.divmensaje {
  width: 300px;
  margin-bottom: 30px;
  margin-top: 30px;
}
div.ismensaje {
  margin-bottom: 0px;
  padding-bottom: 0px;
  padding-right: 0px;
}
.divmensaje p.mensaje {
  font-size: 16px;
  font-weight: 300;
}
.divmensaje p.correo {
  font-size: 12px;
  color: grey;
  font-weight: bold;
  text-align: right;
}
.ismensaje {
  font-size: 16px;
  text-align: right;
}
</style>
