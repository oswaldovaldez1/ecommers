<template>
  <div class="container">
    <b-modal ref="my-modal" hide-footer title="Número de Seguimiento">
      <div class="d-block text-center">
        <div class="d-flex flex-row flex-warp justify-content-between align-items-start">
          <label for="seguimiento" class="mx-2">Número de Seguimiento</label>
          <input
            type="text"
            name="seguimiento"
            id="seguimiento"
            class="form-control mx-2"
            v-model="numReg"
            required
          />
          <button
            :class="['btn', 'btn-outline-rosa', 'mx-2', numReg.length>0?'':'disabled']"
            @click="setNReg"
          >Guardar</button>
        </div>
      </div>
      <div class="d-flex flex-row-reverse flex-warp justify-content-between align-items-start">
        <button class="btn btn-outline-danger" @click="hideModal" style="max-width:100px;">Cerrar</button>
      </div>
    </b-modal>

    <div>
      <div class="busqueda">
        <input
          class="form-control"
          type="text"
          name
          id
          placeholder="Buscar"
          v-model="busqueda2"
          v-on:keyup.enter="buscar"
        />
        <button class="btn btn-outline-info" @click="buscar">
          <i class="fas fa-search"></i>
        </button>
      </div>
      <div class="my-2">
        <a
          href="javascript:void()"
          class="azul"
          v-b-toggle.collapse-x
          variant="primary"
        >Crear Reportes</a>

        <b-collapse id="collapse-x" class="mx-4">
          <div class="row">
            <div class="col-12 col-sm-12 col-md-4">
              <label for="example-input">Fecha inicio</label>
              <b-input-group class="mb-3">
                <b-form-input
                  id="example-input"
                  v-model="inicio"
                  type="text"
                  placeholder="YYYY-MM-DD"
                  autocomplete="off"
                ></b-form-input>
                <b-input-group-append>
                  <b-form-datepicker
                    v-model="inicio"
                    button-only
                    right
                    locale="en-US"
                    aria-controls="example-input"
                  ></b-form-datepicker>
                </b-input-group-append>
              </b-input-group>
            </div>
            <div class="col-12 col-sm-12 col-md-4">
              <label for="example-input">Fecha fin</label>
              <b-input-group class="mb-3">
                <b-form-input
                  id="example-input"
                  v-model="fin"
                  type="text"
                  placeholder="YYYY-MM-DD"
                  autocomplete="off"
                ></b-form-input>
                <b-input-group-append>
                  <b-form-datepicker
                    v-model="fin"
                    button-only
                    right
                    locale="en-US"
                    aria-controls="example-input"
                  ></b-form-datepicker>
                </b-input-group-append>
              </b-input-group>
            </div>
            <div class="col-12 col-sm-12 col-md-8">
              <div class="d-inline-flex">
                <button
                  class="btn btn-outline-rosa mx-2"
                  v-for="(boton, index) in botones"
                  :key="index"
                  @click="getreporte(boton.status)"
                >{{boton.titulo}}</button>
              </div>
            </div>
          </div>
        </b-collapse>
      </div>
    </div>
    <b-table
      :items="items"
      :fields="fields"
      striped
      hover
      responsive
      sticky-header
      @row-clicked="myRowClickHandler"
    >
      <template v-slot:cell(index)="data">{{ data.index + 1 }}</template>
      <template v-slot:cell(status)="row">
        <div>
          <select
            class="form-control"
            :id="row.item.order_number"
            v-model="row.item.status"
            @change="update(row.item)"
          >
            <option value="pendiente">pendiente</option>
            <option value="completado">completado</option>
            <option value="cancelado">cancelado</option>
            <option value="pagado">pagado</option>
            <option value="enviado">enviado</option>
          </select>
        </div>
      </template>
      <template v-slot:cell(pay_amount)="row">
        <span>{{getmount(row.item.pay_amount)}}</span>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="total"
      :per-page="to"
      align="right"
      @input="getOrden(currentPage)"
    ></b-pagination>
    <detalle v-if="indice != -1" :datos="getDatos"></detalle>
  </div>
</template>

<script>
import Sugar from "sugar";
import detalle from "./detalle";
import { mapActions } from "vuex";

export default {
  data() {
    return {
      busqueda2: "",
      inicio: "",
      fin: "",
      item: {},
      currentPage: 1,
      total: 0,
      to: 0,
      items: [],
      datos: {},
      indice: -1,
      numReg: "",
      fields: [
        { key: "index", label: "#", isRowHeader: true },
        { key: "order_number", label: "N° Orden" },
        { key: "method", label: "Metodo" },
        { key: "customer_name", label: "Comprador" },
        { key: "pay_amount", label: "Monto" },
        { key: "status", label: "Status" },
      ],
      botones: [
        {
          titulo: "todo",
          status: "",
        },
        {
          titulo: "pendiente",
          status: "pendiente",
        },
        {
          titulo: "completado",
          status: "completado",
        },
        {
          titulo: "cancelado",
          status: "cancelado",
        },
        {
          titulo: "pagado",
          status: "pagado",
        },
        {
          titulo: "enviado",
          status: "enviado",
        },
      ],
    };
  },
  components: {
    detalle,
  },
  mounted() {
    this.getInfo();
  },

  computed: {
    getDatos() {
      return this.datos;
    },
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
    myRowClickHandler(record, index) {
      this.indice = -1;
      this.indice = index;
      this.datos = this.items[this.indice];
    },
    getreporte(status) {
      if (status === "") {
        status = "-";
      }
      if (this.inicio === "") {
        this.inicio = "-";
      }
      if (this.fin === "") {
        this.fin = "-";
      }
      this.axios({
        url: "/api/reporte/" + status + "/" + this.inicio + "/" + this.fin,
        method: "GET",
        responseType: "blob",
      })
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          if (status === "-") status = "todo";
          this.inicio = "";
          this.fin = "";
          var f = new Date();

          fileLink.setAttribute(
            "download",
            status +
              f.getDate() +
              "-" +
              f.getMonth() +
              "-" +
              f.getFullYear() +
              ".pdf"
          );
          document.body.appendChild(fileLink);

          fileLink.click();
        })
        .catch((error) => {
          this.addClassToast("alert toastdanger");
          this.showToast("No hay registros en la busqueda");
        });
    },
    actualizar(item) {
      item.nseg = this.numReg;
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .put(this.$GlobalUrl + "/api/order/" + item.id, item, {
          headers: {
            Authorization: user.token_type + " " + user.token,
          },
        })
        .then((response) => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.item = {};
          this.numReg = "";
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
    setNReg() {
      this.hideModal();
      this.actualizar(this.item);
    },
    update(item) {
      if (item.status === "enviado") {
        this.item = item;
        this.numReg = item.nseg;
        this.showModal();
      } else {
        this.actualizar(item);
      }
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
    pedido(index) {
      this.indice = -1;
      this.datos = this.valores[index];
      this.indice = index;
    },

    getmount(mount) {
      return Sugar.Number.format(mount, 2);
    },
    getOrden(index) {
      this.axios
        .get(this.$GlobalUrl + "/api/order?page=" + index)
        .then((response) => {
          this.items = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getInfo() {
      this.axios
        .get(this.$GlobalUrl + "/api/order")
        .then((response) => {
          this.items = response.data.data;
          this.currentPage = response.data.current_page;
          this.total = response.data.total;
          this.to = response.data.to;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    buscar() {
      this.axios
        .get(this.$GlobalUrl + "/api/order/busqueda/" + this.busqueda2)
        .then((response) => {
          this.items = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
.dropdown-menu.p-2.show.dropdown-menu-right {
  background-color: white;
}
</style>
