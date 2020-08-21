<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-navbar-brand href="#">
        <b-button v-b-toggle.sidebarmenu>
          <span class="navbar-toggler-icon"></span>
        </b-button>
      </b-navbar-brand>
      <div class="d-flex flex-row align-content-end justify-content-end align-items-end">
        <router-link :to="{ name: 'home' }" style="float: right;">
          <i class="fas fa-home blanco"></i>
        </router-link>
        <button class="mx-4" style="color:white; float: right;" @click="closeSession">
          <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    </b-navbar>
    <b-sidebar
      id="sidebarmenu"
      aria-labelledby="sidebar-no-header-title"
      shadow
      bg-variant="gris"
      text-variant="light"
    >
      <div class="px-3 py-4 fondo">
        <!-- <b-avatar src="https://placekitten.com/300/300" size="4rem"></b-avatar> -->
        <div class="divinfo">
          <a href="javascript:void()" class="nombreUsuario">
            {{
            name
            }}
          </a>
          <br />
          <a href="javascript:void()" class="mailUsuario">
            {{
            mail
            }}
          </a>
        </div>
      </div>
      <hr class="linea" />
      <div>
        <b-list-group>
          <b-list-group-item v-for="(menu, index) of menus" :key="index" variant="azul">
            <div block v-b-toggle.v-b-toggle="'accordion-' + index" class="t-categorias">
              <span>{{ menu.titulo }}</span>
            </div>
            <b-collapse :id="'accordion-' + index" accordion="my-accordion" class="mt-2">
              <b-list-group class="submenu" v-for="(subnivel, index) of menu.subnivel" :key="index">
                <b-list-group-item
                  class="submenuitem"
                  :to="{
                                        name: subnivel.name,
                                        params: { opcion: subnivel.opcion }
                                    }"
                >{{ subnivel.titulo }}</b-list-group-item>
                <hr class="linea" />
              </b-list-group>
            </b-collapse>
          </b-list-group-item>
        </b-list-group>
      </div>
    </b-sidebar>
  </div>
</template>
<script>
export default {
  data() {
    return {
      mail: "",
      name: "",
      menus: [
        {
          titulo: "Dashboard",
          subnivel: [
            {
              titulo: "home",
              name: "admin",
              opcion: "dashboard"
            }
          ]
        },
        {
          titulo: "Usuario",
          subnivel: [
            {
              titulo: "Crear",
              name: "admin",
              opcion: "crearusuario"
            },
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarusuarios"
            }
          ]
        },
        {
          titulo: "Productos",
          subnivel: [
            {
              titulo: "Crear",
              name: "admin",
              opcion: "crearproducto"
            },
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarproductos"
            }
          ]
        },
        {
          titulo: "Pedidos",
          subnivel: [
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarpedidos"
            }
          ]
        },
        {
          titulo: "Categorias",
          subnivel: [
            {
              titulo: "Crear",
              name: "admin",
              opcion: "crearcategoria"
            },
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarcategorias"
            }
          ]
        },
        {
          titulo: "Marcas",
          subnivel: [
            {
              titulo: "Crear",
              name: "admin",
              opcion: "crearmarca"
            },
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarmarcas"
            }
          ]
        },
        {
          titulo: "Provedores",
          subnivel: [
            {
              titulo: "Crear",
              name: "admin",
              opcion: "crearprovedor"
            },
            {
              titulo: "Listar",
              name: "admin",
              opcion: "listarprovedores"
            }
          ]
        },
        {
          titulo: "Configuracion",
          subnivel: [
            {
              titulo: "Configuracion",
              name: "admin",
              opcion: "configuracion"
            }
          ]
        }
      ]
    };
  },
  created() {},
  mounted() {
    if (this.$session.has("user")) {
      let user = JSON.parse(this.$session.get("user"));
      this.mail = user.email;
      this.name = user.name;
    } else {
      this.$router.push({
        name: "login"
      });
    }
  },
  methods: {
    closeSession() {
      if (this.$session.has("user")) {
        let user = JSON.parse(this.$session.get("user"));
        this.axios
          .get("/api/auth/logout", {
            headers: {
              Authorization: user.token_type + " " + user.token
            }
          })
          .then(response => {
            console.log(response.data);
          })
          .catch(error => {
            console.error(error.response.data);
          });

        this.$session.remove("user");
        this.$router.push({
          name: "login"
        });
      }
    }
  }
};
</script>
<style>
.navbar-expand-lg {
  justify-content: space-between !important;
}
.submenuitem {
  border: 1px transparent;
}
.listagrupo {
  border-radius: 0%;
}
.cart,
.cart > a,
.cart a {
  width: max-content;
}
.dropdown-menu.show,
.dropdown-menu.dropdown-menu-right.show {
  background-color: #335da8;
  padding-top: 0rem;
  padding-bottom: 0rem;
  border-radius: 0rem;
}
.dropdown-item {
  color: white;
  font-family: "Jost";
  font-weight: 400;
}
.dropdown-item:hover {
  background-color: #5a83c2;
  color: white;
}

.logo {
  position: fixed;
  top: 0px;
  background-color: #5a83c2;
  border-radius: 0rem 0rem 0.7rem 0.7rem;
  border-top: 0.4rem solid #335da8;
}
@media (min-width: 320px) {
  .logosvg,
  .logotam {
    width: 77px;
  }

  .busqueda-input {
    margin-top: 0.5rem;
  }
}
@media (min-width: 700px) {
  .logosvg,
  .logotam {
    width: 80px;
  }
  .busqueda-input {
    margin-top: 0rem;
  }
}

@media (min-width: 1200px) {
  .logosvg,
  .logotam {
    width: 114px;
  }
}

.dropdown-toggle::after {
  border-top-color: transparent;
}
.t-categorias,
.navbar-dark .navbar-nav .nav-link {
  color: white;
  font-family: "Jost";
  font-weight: 600;
}
.navbar-dark .navbar-nav .nav-link:hover,
.navbar-dark .navbar-nav .nav-link:focus {
  color: #ef7968 !important;
}
.t-categorias[aria-expanded="true"],
.navbar-dark .navbar-nav .nav-link[aria-expanded="true"] {
  color: #ef7968;
}
.list-group-item {
  background-color: #18181e;
}
.submenu a {
  color: white;
}
.list-group-item-action:hover,
.list-group-item-action:focus,
.submenu a:hover,
.submenu:hover {
  background-color: #5a83c2;
  color: white;
  font-family: "Jost";
  font-weight: 600;
}
.blanco {
  color: white !important;
}
.bt-gris {
  color: #fff !important;
  background-color: #18181e;
  border-color: #18181e;
}
#sidebarmenu {
  height: 100vh !important;
  overflow: auto;
}

.dropdown-item,
.submenu,
.t-categorias,
.nav-link.dropdown-toggle {
  font-size: 12px;
}
</style>
