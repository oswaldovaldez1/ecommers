<template>
  <div class="container">
    <listarpedido v-if="opview === 'listarpedidos'"></listarpedido>
    <nuevousuario v-else-if="opview==='crearusuario'"></nuevousuario>
    <nuevousuario v-else-if="opview==='actualizarusuario'" :id.number="id"></nuevousuario>
    <listarusuario v-else-if="opview==='listarusuarios'"></listarusuario>
    <nuevoproducto v-else-if="opview==='crearproducto'"></nuevoproducto>
    <nuevoproducto v-else-if="opview==='actualizarproducto'" :id.number="id"></nuevoproducto>
    <listarproductos v-else-if="opview==='listarproductos'"></listarproductos>
    <nuevacategoria v-else-if="opview==='crearcategoria'"></nuevacategoria>
    <nuevacategoria v-else-if="opview==='actualizarcategoria'" :id.number="id"></nuevacategoria>
    <listarcategorias v-else-if="opview==='listarcategorias'"></listarcategorias>
    <nuevamarca v-else-if="opview==='crearmarca'"></nuevamarca>
    <nuevamarca v-else-if="opview==='actualizarmarca'" :id.number="id"></nuevamarca>
    <listarmarcas v-else-if="opview==='listarmarcas'"></listarmarcas>
    <nuevoprovedor v-else-if="opview==='crearprovedor'"></nuevoprovedor>
    <nuevoprovedor v-else-if="opview==='actualizarprovedor'" :id.number="id"></nuevoprovedor>
    <listarprovedores v-else-if="opview==='listarprovedores'"></listarprovedores>
    <config v-else-if="opview==='configuracion'"></config>
    <api v-else-if="opview==='api'"></api>
    <home v-else></home>
  </div>
</template>
<script>
import home from "./Home/home";
//usuarios
import listarusuario from "./Usuarios/ListarUsuarios";
import nuevousuario from "./Usuarios/Nuevousuario";
//productos
import nuevoproducto from "./Productos/NuevoProducto";
import listarproductos from "./Productos/ListarProductos";
//pedidos
import listarpedido from "./Pedidos/listar";
//categorias
import nuevacategoria from "./Categorias/NuevaCategoria";
import listarcategorias from "./Categorias/ListarCategorias";
//configuracion
import config from "./Configuracion/config";
import api from "./Configuracion/Api";
//marcas
import nuevamarca from "./Marcas/CrearMarca";
import listarmarcas from "./Marcas/ListarMarcas";
//provedores
import nuevoprovedor from "./Provedores/CrearProvedor";
import listarprovedores from "./Provedores/ListarProvedores";

export default {
  data() {
    return {
      email: "",
      password: "",
      opview: "",
      id: 0
    };
  },
  created() {},
  components: {
    listarusuario,
    nuevousuario,
    nuevoproducto,
    listarproductos,
    listarpedido,
    nuevacategoria,
    listarcategorias,
    config,
    api,
    home,
    nuevamarca,
    listarmarcas,
    nuevoprovedor,
    listarprovedores
  },
  watch: {
    "$route.params.opcion": {
      handler: function(opcion) {
        this.opview = opcion;
      },
      deep: true,
      immediate: true
    },
    "$route.params.id": {
      handler: function(id) {
        this.id = id;
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    login() {
      this.axios
        .post(this.$GlobalUrl + "/api/auth/login", {
          email: this.email,
          password: this.password
        })
        .then(response => {
          let datos = response.data;

          this.$autorizacion = datos.token_type + " " + datos.access_token;
        })
        .catch(error => {
          console.log(error);
        });
    },
    signup() {},
    logout() {},
    getproductos() {
      this.axios
        .get(this.$GlobalUrl + "/api/category", {
          headers: {
            Authorization: this.$autorizacion
          }
        })
        .then(response => {
          //console.log(response.data);
        })
        .catch(error => {
          console.log("Error ====get====>", error);
        });
    }
  }
};
</script>
