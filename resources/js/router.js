import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import Home from "./views/home/Home.vue";
import About from "./views/About.vue";
//import prueba from './views/prueba.vue'
import Products from "./views/Productos/Products.vue";
import error from "./views/404.vue";
import DashBoard from "./views/Admin/DashBoard.vue";
import checkout from "./views/Cart/checkout.vue";
import Payment from "./views/Payment/Payment.vue";
import Actualizar from "./views/gonher/Actualizar.vue";
import ProductDetails from "./views/Productos/ProductDetails.vue";
import privacidad from "./views/legal/privacidad.vue";
import terminos from "./views/legal/terminos.vue";
import login from "./views/login/index.vue";
import user from "./views/Usuarios/DashBoardUser.vue";
const router = new VueRouter({
    mode: "history",

    routes: [
        {
            path: "/user",
            name: "user",
            component: user
        },
        {
            path: "/login",
            name: "login",
            component: login
        },
        {
            path: "/recuperacion/:token",
            name: "recuperacion",
            component: login
        },
        {
            path: "/terminos-y-condiciones",
            name: "terminos",
            component: terminos
        },
        {
            path: "/aviso-de-privacidad",
            name: "privacidad",
            component: privacidad
        },
        {
            path: "/producto/:sku",
            name: "productinfo",
            component: ProductDetails
        },
        {
            path: "/actualizar",
            name: "actualizar",
            component: Actualizar
        },
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/payment",
            name: "payment",
            component: Payment
        },
        {
            path: "/about",
            name: "about",
            component: About
        },
        {
            path: "/productos/:busqueda",
            name: "productos",
            component: Products
        },
        {
            path: "/categoria/:category",
            name: "categoria",
            component: Products
        },
        {
            path: "/subcategoria/:category",
            name: "subcategoria",
            component: Products
        },
        {
            path: "/cart/:options",
            name: "checkout",
            component: checkout
        },
        /*{
                path: "/admin",
                name: "admin",
                component: DashBoard
            },*/
        {
            path: "/admin/:opcion/:id?",
            name: "admin",
            component: DashBoard,
            meta: { requiresAuth: true }
        },
        {
            path: "*",
            name: "404",
            component: error
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        let isAdmin = false;

        if (router.app.$session.has("user")) {
            let user = JSON.parse(router.app.$session.get("user"));
            if (user.user_type === "Admin" || user.user_type === "Vendedor") {
                isAdmin = true;
            }
        }

        if (!isAdmin) {
            next({
                path: "/",
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;
