<template>
  <div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Eliminar Usuario</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            ¿Desea Eliminar al usuario del correo
            {{ id > -1 ? items[id].email : "" }}?
          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-success"
              @click="deleteUser()"
              data-dismiss="modal"
            >Eliminar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <div class="busqueda">
      <input
        class="form-control"
        type="text"
        name
        id
        placeholder="Buscar"
        v-model="busqueda"
        v-on:keyup.enter="buscar"
      />
      <button class="btn btn-outline-info" @click="buscar">
        <i class="fas fa-search"></i>
      </button>
    </div>
    <b-table :items="items" :fields="fields" striped hover responsive sticky-header>
      <template v-slot:cell(index)="data">{{ data.index + 1 }}</template>
      <template v-slot:cell(id_rol)="row">
        <div>
          <span v-if="row.item.id_rol === 1">Admin</span>
          <span v-if="row.item.id_rol === 2">Vendedores</span>
          <span v-if="row.item.id_rol === 3">Comprador</span>
        </div>
      </template>
      <template v-slot:cell(editar)="row">
        <router-link
          :to="{
                        name: 'admin',
                        params: { opcion: 'actualizarusuario', id: row.item.id }
                    }"
          v-if="row.item.id_rol!=3"
        >
          <button class="btn btn-info">Editar</button>
        </router-link>
      </template>
      <template v-slot:cell(eliminar)="row">
        <button
          v-if="row.item.id_rol!=3"
          class="btn btn-danger"
          @click="modal(row.item.id)"
          data-toggle="modal"
          data-target="#myModal"
        >Eliminar</button>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="total"
      :per-page="to"
      align="right"
      @input="getUsers(currentPage)"
    ></b-pagination>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  data() {
    return {
      busqueda: "",
      items: [],
      id: -1,
      currentPage: 1,
      total: 0,
      to: 0,
      fields: [
        { key: "index", label: "#", isRowHeader: true },
        {
          key: "firstname",
          label: "Nombre"
        },
        { key: "secondname", label: "Apellido" },
        { key: "email", label: "Correo" },
        { key: "id_rol", label: "Perfil" },
        { key: "editar", label: "Editar" },
        { key: "eliminar", label: "Eliminar" }
      ]
    };
  },
  methods: {
    ...mapActions(["addClassToast", "showToast", "showOrHiddenModal"]),
    modal(id) {
      this.id = id;
    },
    buscar() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/user/busqueda/" + this.busqueda, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.items = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getUser() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/user", {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.items = response.data.data;
          this.currentPage = response.data.current_page;
          this.total = response.data.total;
          this.to = response.data.to;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getUsers(index) {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .get(this.$GlobalUrl + "/api/user?page=" + index, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.items = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    deleteUser() {
      let user = JSON.parse(this.$session.get("user"));
      this.axios
        .delete(this.$GlobalUrl + "/api/user/" + this.id, {
          headers: {
            Authorization: user.token_type + " " + user.token
          }
        })
        .then(response => {
          this.addClassToast("alert toastsuccess");
          this.showToast(response.data.message);
          this.getUser();
        })
        .catch(error => {
          this.addClassToast("alert toasterror");
          this.showToast(error.response.data);
        });
      this.getUser();
    }
  },
  mounted() {
    this.getUser();
  }
};
</script>
<style>
*::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

*::-webkit-scrollbar-thumb {
  background: #5a83c2;
  border-radius: 4px;
}

*::-webkit-scrollbar-thumb:hover {
  background: #335da8;
  box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
}

*::-webkit-scrollbar-thumb:active {
  background-color: #335da8;
}

*::-webkit-scrollbar-track {
  /* background: #e1e1e1; */
  background: transparent !important;
}

*::-webkit-scrollbar-track:hover,
*::-webkit-scrollbar-track:active {
  /* background: #d4d4d4; */
  background: transparent !important;
}
.busqueda {
  display: flex;
  width: 200px;
  padding: 20px;
}
</style>
