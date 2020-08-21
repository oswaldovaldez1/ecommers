//import "bootstrap-vue/dist/bootstrap-vue.css";
import Vue from "vue";
import Vuetify from "vuetify";
import VueAxios from "vue-axios";
import axios from "axios";
import VueHolder from "vue-holderjs";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import GSignInButton from "vue-google-signin-button";
import VueSession from "vue-session";
import VueMask from "v-mask";
import Magnifier from "vuejs-magnifier";
import ProductZoomer from "vue-product-zoomer";
import SocialSharing from "vue-social-sharing";
import VAnimateCss from "v-animate-css";
import vWow from "v-wow";
import VueYoutube from "vue-youtube";
import LoadScript from "vue-plugin-load-script";
import excel from "vue-excel-export";
import CKEditor from "@ckeditor/ckeditor5-vue";

const $ = require("jquery");

window.$ = $;

Vue.use(CKEditor);
Vue.use(excel);
Vue.use(LoadScript);
Vue.use(VueYoutube);
Vue.use(VAnimateCss);
Vue.use(vWow);
Vue.use(SocialSharing);
Vue.use(Vuetify);
Vue.use(ProductZoomer);
Vue.use(VueSession, {
    persist: true
});
Vue.use(GSignInButton);
Vue.use(VueHolder);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueAxios, axios);
Vue.use(VueMask);
Vue.use(Magnifier);

Vue.prototype.$GlobalUrl = ""; //"http://localhost:8000";
Vue.prototype.$autorizacion = "";

import { store } from "./store";
import router from "./router";

Vue.component("navigation", require("./components/Navigation.vue").default);
Vue.component("toast", require("./components/Toast.vue").default);
Vue.component("whatsapp", require("./components/Whatsapp.vue").default);
Vue.component("vfooter", require("./components/vfooter.vue").default);

const app = new Vue({
    el: "#app",
    vuetify: new Vuetify({
        icons: {
            iconfont: "md"
        }
    }),
    store,
    router, //: new VueRouter(routes),
    components: {},
    created() {
        if (!this.$session.exists()) {
            this.$session.start();
        }
    },
    mounted() {
        if (this.$session.has("cart")) {
            this.$store.dispatch(
                "addCart",
                JSON.parse(this.$session.get("cart"))
            );
        }
        if (this.$session.has("wishlist")) {
            this.$store.dispatch(
                "addWishLists",
                JSON.parse(this.$session.get("wishlist"))
            );
        }
    },
    computed: {
        toast() {
            return this.$store.getters.toast;
        }
    },
    methods: {
        hideToast() {
            this.$store.commit("HIDETOAST");
        }
    }
});
