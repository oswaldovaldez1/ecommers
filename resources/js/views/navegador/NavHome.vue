<template>
  <div>
    <b-navbar toggleable="xl" type="dark" variant="gris" fixed="top">
      <b-navbar-brand href="#">
        <div class="logotam"></div>
        <router-link :to="{ name: 'home' }">
          <div class="logo" v-animate-css="'fadeInDown'">
            <img src="/img/Lanesalogo.svg" alt="logo" class="logosvg" />
          </div>
        </router-link>
      </b-navbar-brand>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <li
            v-for="(categoria, index) of categorias"
            :key="index"
            class="nav-item b-nav-dropdown dropdown"
            left
            :id="categoria.titulo + '_' + index"
            @mouseover="hoverOver(categoria.titulo + '_' + index)"
            @mouseleave="hoverOut(categoria.titulo + '_' + index)"
            v-if="categoria.status==='up'"
          >
            <a
              aria-haspopup="true"
              aria-expanded="false"
              target="_self"
              href="#"
              class="nav-link dropdown-toggle"
              :id="categoria.titulo + '_' + index + '_btn'"
            >
              <span>{{ categoria.titulo }}</span>
            </a>
            <ul
              :id="categoria.titulo + '_' + index + '_ul'"
              tabindex="-1"
              class="dropdown-menu"
              :aria-labelledby="
                                categoria.titulo + '_' + index + '_btn'
                            "
              @mouseover="
                                hoverOver(categoria.titulo + '_' + index)
                            "
              @mouseleave="
                                hoverOut(categoria.titulo + '_' + index)
                            "
            >
              <li
                v-for="(subnivel, index2) of categoria.subnivel"
                :key="index2"
                role="presentation"
                class="menu"
                @mouseover="
                                    hoverOvertwo(
                                        subnivel.titulo + '_sub_' + index2
                                    )
                                "
                @mouseleave="
                                    hoverOuttwo(
                                        subnivel.titulo + '_sub_' + index2,
                                        categoria.titulo + '_' + index
                                    )
                                "
                v-if="subnivel.status==='up'"
              >
                <router-link
                  :to="{
                                        name: 'categoria',
                                        params: { category: subnivel.categoria }
                                    }"
                >
                  <a
                    href="#"
                    aria-haspopup="true"
                    aria-expanded="false"
                    :id="
                                            subnivel.titulo +
                                                '_sub_' +
                                                index2 +
                                                '_btn'
                                        "
                    class="dropdown-item"
                    role="menuitem"
                    target="_self"
                  >{{ subnivel.titulo }}</a>
                </router-link>
                <ul
                  v-if="subnivel.subnivel.length > 0"
                  :id="
                                        subnivel.titulo +
                                            '_sub_' +
                                            index2 +
                                            '_ul'
                                    "
                  tabindex="-2"
                  class="dropdown-menu sub-menu"
                  :aria-labelledby="
                                        subnivel.titulo +
                                            '_sub_' +
                                            index2 +
                                            '_btn'
                                    "
                >
                  <li
                    v-for="(subniveltwo,
                                        index3) of subnivel.subnivel"
                    :key="index3"
                    role="presentation"
                    class="menu"
                    v-if="subniveltwo.status==='up'"
                  >
                    <router-link
                      :to="{
                                                name: 'subcategoria',
                                                params: {
                                                    category:
                                                        subniveltwo.categoria
                                                }
                                            }"
                    >
                      <a
                        href="#"
                        class="dropdown-item"
                        role="menuitem"
                        target="_self"
                      >{{ subniveltwo.titulo }}</a>
                    </router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
        </b-navbar-nav>

        <b-navbar-nav class="ml-auto">
          <b-form-input
            size="sm"
            class="mr-sm-2 mt-sm-1"
            placeholder="Buscar"
            v-model="busqueda"
            v-on:keyup.enter="buscar"
          ></b-form-input>
          <b-button
            size="sm"
            class="my-1 my-sm-0"
            variant="azul-claro"
            @click="busquedaClick"
          >Buscar</b-button>
        </b-navbar-nav>
      </b-collapse>
      <div>
        <router-link :to="{ name: getUrl }">
          <b-button variant="gris" style="color:white" id="btnWishList">
            <i class="far fa-user blanco" aria-hidden="true"></i>
          </b-button>
        </router-link>

        <router-link :to="{ name: 'checkout', params: { options: 'wishlist' } }">
          <b-button variant="gris" style="color:white" id="btnWishList">
            <i class="far fa-heart blanco" aria-hidden="true"></i>
            <div class="divBase bg-azul-claro" v-if="wishListSize > 0">
              <span class="numeroIcon">{{ wishListSize }}</span>
            </div>
          </b-button>
        </router-link>
        <router-link :to="{ name: 'checkout', params: { options: 'checkout' } }">
          <b-button variant="gris" style="color:white" id="btnCart">
            <i class="fa fa-shopping-cart blanco" aria-hidden="true"></i>
            <div class="divBase bg-azul-claro" v-if="cartSize > 0">
              <span class="numeroIcon">{{ cartSize }}</span>
            </div>
          </b-button>
        </router-link>

        <b-button
          variant="gris"
          style="color:white"
          id="btnCart"
          @click="closeSession"
          v-if="logged"
        >
          <i class="fas fa-sign-out-alt blancos"></i>
        </b-button>

        <b-button variant="gris" v-b-toggle.sidebarmenu v-if="bottonmenu">
          <span class="navbar-toggler-icon"></span>
        </b-button>
      </div>
    </b-navbar>
    <b-sidebar
      id="sidebarmenu"
      aria-labelledby="sidebar-no-header-title"
      shadow
      bg-variant="gris"
      text-variant="light"
    >
      <div class="px-3 py-4 fondo" fixed="true">
        <b-avatar src="/img/Lanesalogo.svg" size="4rem"></b-avatar>
        <div class="divinfo">
          <a href="javascript:void()" class="nombreUsuario">LANESA1957</a>
          <br />
          <a href="mailto:lanesa1957@live.com.mx" class="mailUsuario">lanesa1957@live.com.mx</a>
        </div>
      </div>
      <hr class="linea" />
      <div>
        <b-list-group class="listagrupo">
          <b-list-group-item>
            <b-row>
              <b-col cols="8">
                <b-form-input
                  size="sm"
                  class="mr-sm-2 busqueda-input"
                  placeholder="Buscar"
                  v-model="busqueda"
                  v-on:keyup.enter="buscar"
                ></b-form-input>
              </b-col>
              <b-col cols="4">
                <b-button
                  size="sm"
                  class="my-2 my-sm-0"
                  variant="azul-claro"
                  @click="busquedaClick"
                >Buscar</b-button>
              </b-col>
            </b-row>
          </b-list-group-item>

          <b-list-group-item v-for="(categoria, index) of categorias" :key="index" variant="azul">
            <div
              block
              v-b-toggle.v-b-toggle="'accordion-' + index"
              class="t-categorias"
              v-if="categoria.status==='up'"
            >
              <span>{{ categoria.titulo }}</span>
            </div>
            <b-collapse :id="'accordion-' + index" accordion="my-accordion" class="mt-2">
              <b-list-group
                class="submenu"
                v-for="(subnivel, index2) of categoria.subnivel"
                :key="index2"
              >
                <b-list-group-item
                  v-if="(subnivel.subnivel.length > 0) && (subnivel.status==='up')"
                  class="submenuitem"
                >
                  <div
                    block
                    v-b-toggle.v-b-toggle="
                                            'accordion-sub-' + index2
                                        "
                    class="t-sub-categorias"
                  >
                    <span>{{ subnivel.titulo }}</span>
                  </div>
                  <b-collapse
                    :id="'accordion-sub-' + index2"
                    accordion="my-accordion-2"
                    class="mt-2"
                  >
                    <b-list-group
                      class="submenu"
                      v-for="(subniveltwo,
                                            index3) of subnivel.subnivel"
                      :key="index3"
                    >
                      <b-list-group-item
                        class="submenuitem"
                        :to="{
                                                    name: 'categoria',
                                                    params: {
                                                        category:
                                                            subniveltwo.categoria
                                                    }
                                                }"
                        v-if="subniveltwo.status==='up'"
                      >{{ subniveltwo.titulo }}</b-list-group-item>
                    </b-list-group>
                  </b-collapse>
                </b-list-group-item>
                <b-list-group-item
                  v-else-if="subnivel.status==='up'"
                  class="submenuitem"
                  :to="{
                                        name: 'categoria',
                                        params: { category: subnivel.categoria }
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
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      busqueda: "",
      bottonmenu: true,
      categorias: [],
      logged: false,
      intervalid1: ""
    };
  },
  created() {
    this.$GlobalUrl = window.location.host;
    if (window.innerWidth >= 1026) {
      this.bottonmenu = false;
    } else {
      this.bottonmenu = true;
    }
    this.getMenu();
  },
  mounted() {
    this.getSessionHas();
    window.addEventListener("resize", () => {
      if (window.innerWidth >= 1026) {
        this.bottonmenu = false;
        //let menumovil = document.querySelector("#sidebarmenu");
      } else {
        this.bottonmenu = true;
        //let menumovil = document.querySelector("#sidebarmenu");
      }
    });
    this.getMenu();
  },
  computed: {
    ...mapGetters(["cartSize", "wishListSize"]),
    getUrl() {
      if (this.logged) {
        let user = JSON.parse(this.$session.get("user"));
        if (user.user_type === "Comprador") {
          return "user";
        } else {
          return "login";
        }
      } else {
        return "login";
      }
    }
  },
  methods: {
    getSessionHas() {
      this.intervalid1 = setInterval(() => {
        this.logged = this.$session.has("user");
        //console.log(this.$session.has("user"));
      }, 1000);
    },
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
          name: "home"
        });
      }
    },
    getMenu() {
      this.axios
        .get("/api/category/menu")
        .then(response => {
          this.categorias = response.data;
        })
        .catch(error => {
          console.log("Error ====get====>", error);
        });
    },
    busquedaClick() {
      this.$router.push({
        path: `/productos/${this.busqueda.split(" ").join("-")}`
      });
    },
    buscar: function(event) {
      this.busquedaClick();
    },
    hoverOvertwo: function(id) {
      let menuLink = document.getElementById(id + "_btn");
      let menuUl = document.getElementById(id + "_ul");
      if (document.body.contains(menuLink)) {
        menuLink.setAttribute("aria-expanded", "true");
      }
      if (document.body.contains(menuUl)) {
        menuUl.classList.add("show");
      }
    },
    hoverOver: function(id) {
      let menuLink = document.getElementById(id + "_btn");
      let menuUl = document.getElementById(id + "_ul");
      if (document.body.contains(menuLink)) {
        menuLink.setAttribute("aria-expanded", "true");
      }
      if (document.body.contains(menuUl)) {
        menuUl.classList.add("show");
      }
    },
    hoverOuttwo: function(id, idcat) {
      let menuLink = document.getElementById(id + "_btn");
      let menuUl = document.getElementById(id + "_ul");
      if (document.body.contains(menuLink)) {
        menuLink.setAttribute("aria-expanded", "false");
      }
      if (document.body.contains(menuUl)) {
        menuUl.classList.remove("show");
      }
      let menu2 = document.getElementById(idcat);
      let menuLink2 = document.getElementById(idcat + "_btn");
      let menuUl2 = document.getElementById(idcat + "_ul");
      if (document.body.contains(menu2)) {
        menu2.classList.remove("show");
      }
      if (document.body.contains(menuUl2)) {
        menuUl2.classList.remove("show");
      }
      if (document.body.contains(menuLink2)) {
        menuLink2.setAttribute("aria-expanded", "false");
      }
    },
    hoverOut: function(id) {
      let menu = document.getElementById(id);
      let menuLink = document.getElementById(id + "_btn");
      let menuUl = document.getElementById(id + "_ul");
      if (document.body.contains(menu)) {
        menu.classList.remove("show");
      }
      if (document.body.contains(menuUl)) {
        menuUl.classList.remove("show");
      }
      if (document.body.contains(menuLink)) {
        menuLink.setAttribute("aria-expanded", "false");
      }
    }
  }
};
</script>
<style>
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
.dropdown-menu,
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
.dropdown-item:hover,
.dropdown-item[aria-expanded="true"] {
  background-color: #5a83c2;
  color: white;
}

.logo {
  position: fixed;
  top: 0px;
  background-color: white !important;
  border-radius: 0rem 0rem 0.7rem 0.7rem;
  border-top: 0.4rem solid #335da8 !important;
  border-left: 0.1rem solid #335da8 !important;
  border-right: 0.1rem solid #335da8 !important;
  border-bottom: 0.1rem solid #335da8 !important;
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
.dropdown:hover,
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
.t-sub-categorias[aria-expanded="true"] {
  color: #5a83c2;
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
.b-avatar.badge-secondary.rounded-circle {
  background-color: transparent;
  border: 1px solid white;
}
.dropdown-menu {
  top: 95%;
}
/*
.dropdown:hover > .dropdown-menu {
  display: block;
}
.dropdown > .dropdown-toggle:active {
  pointer-events: none;
} */
.list-group-item:first-child {
  border-top-left-radius: 0rem !important;
  border-top-right-radius: 0rem !important;
}
#btnWishList,
#btnCart {
  position: relative;
}
.divBase {
  width: 20px;
  height: 20px;
  display: inline-block;
  padding-bottom: 1px;
  border-radius: 100%;
  position: absolute;
  top: 1%;
  right: -5px;
  z-index: 0;
}

.numeroIcon {
  font-size: 11px;
}
</style>
