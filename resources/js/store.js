import Vue from "vue";
import Vuex from "vuex";
import Sugar from "sugar";
Vue.use(Vuex);

//import { ShoppingCartModule } from "./shopping_cart_module";

export const store = new Vuex.Store({
    state: {
        cartProducts: [],
        cartProductsWishList: [],
        Products: [],
        toast: {
            text: "",
            show: false,
            classToast: "alert toastdefault"
        },
        showModal: false,
        showPopupCart: false,
        search: "",
        currenPage: 1,
        paginas: 1,
        conf: {}
    },
    getters: {
        getFavorites: state => {
            return state.conf.mprod;
        },
        getSlider: state => {
            return state.conf.slider;
        },
        getConfig: state => {
            return state.conf;
        },
        toast: state => {
            return state.toast;
        },
        getPaginas: state => {
            return state.paginas;
        },
        getCurrentPage: state => {
            return state.currenPage;
        },
        getProducts: state => {
            return state.Products;
        },
        getcurrentProduct: state => sku => {
            var record = state.Products.find(p => p.id === sku);
            if (!record) {
                return undefined;
            } else {
                return record;
            }
        },
        getTotalProducts: state => {
            return state.Products.length;
        },
        getProductInCart: state => state.cartProducts,
        getShowModal: state => state.showModal,
        getPopupCart: state => state.showPopupCart,
        getProducQtytById: state => index => {
            return state.cartProducts[index].cantidad;
        },
        cartSize: state => {
            return state.cartProducts.length;
        },
        wishListSize: state => {
            return state.cartProductsWishList.length;
        },
        findWishList: state => product => {
            return state.cartProductsWishList.findIndex(
                p => p.id === product.id
            );
        },
        total: state => envio => {
            return Sugar.Number.format(
                state.cartProducts.reduce((total, product) => {
                    return total + product.Precio * product.cantidad;
                }, 0) + envio,
                2
            );
        },
        getSearch: state => {
            return state.search;
        }
    },
    mutations: {
        ADD_PAGINAS(state, paginas) {
            state.paginas = paginas;
        },
        ADD_CURRENT_PAGE(state, page) {
            state.currenPage = page;
        },
        ADD_SEARCH(state, search) {
            state.search = search;
        },
        ADD_PRODUCTS(state, products) {
            state.Products = products;
        },
        ADDCLASSTOAST(state, classToast) {
            state.toast.classToast = classToast;
        },
        SHOWTOAST: (state, toastText) => {
            state.toast.show = true;
            state.toast.text = toastText;
        },
        HIDETOAST: state => {
            state.toast.show = false;
            state.toast.text = "";
        },
        ADD_CART: (state, products) => {
            state.cartProducts = products;
        },
        ADD_WISHLISTS: (state, products) => {
            state.cartProductsWishList = products;
        },
        ADD_WISHLIST: (state, product) => {
            state.cartProductsWishList.push(product);
            store._vm.$session.set(
                "wishlist",
                JSON.stringify(state.cartProductsWishList)
            );
        },

        REMOVE_WISHLIST: (state, product) => {
            var index = state.cartProductsWishList.findIndex(
                p => p.id === product.id
            );

            state.cartProductsWishList.splice(index, 1);
            store._vm.$session.set(
                "wishlist",
                JSON.stringify(state.cartProductsWishList)
            );
        },
        ADD_PRODUCT: (state, product) => {
            var record = state.cartProducts.find(p => p.id === product.id);

            if (!record) {
                product.cantidad = 1;
                state.cartProducts.push(product);
            } else {
                record.cantidad++;
            }
            store._vm.$session.set("cart", JSON.stringify(state.cartProducts));
        },
        ADD_ITEM: (state, product) => {
            var index = state.cartProducts.findIndex(p => p.id === product.id);
            product.cantidad++;
            state.cartProducts.splice(index, 1, product);
            store._vm.$session.set("cart", JSON.stringify(state.cartProducts));
        },
        REMOVE_ITEM: (state, product) => {
            var index = state.cartProducts.findIndex(p => p.id === product.id);
            product.cantidad--;
            state.cartProducts.splice(index, 1, product);
            store._vm.$session.set("cart", JSON.stringify(state.cartProducts));
        },
        REMOVE_PRODUCT: (state, index) => {
            state.cartProducts.splice(index, 1);
            store._vm.$session.set("cart", JSON.stringify(state.cartProducts));
        },
        REMOVE_ALL: state => {
            state.cartProducts = [];
            store._vm.$session.set("cart", JSON.stringify(state.cartProducts));
        },
        SHOW_MODAL: state => {
            state.showModal = !state.showModal;
        },
        SHOW_POPUP_CART: state => {
            state.showPopupCart = !state.showPopupCart;
        },
        UPDATE_CONF: (state, conf) => {
            if (conf.paypal_production === null) {
                conf.paypal_production = "";
            }
            if (conf.slider === null) {
                conf.slider = "";
            }
            if (conf.mprod === null) {
                conf.mprod = "";
            }
            state.conf = conf;
        }
    },
    actions: {
        updateconfig(context, conf) {
            context.commit("UPDATE_CONF", conf);
        },
        addPaginas(context, paginas) {
            context.commit("ADD_PAGINAS", paginas);
        },
        addPage(context, page) {
            context.commit("ADD_CURRENT_PAGE", page);
        },
        addCart: (context, products) => {
            context.commit("ADD_CART", products);
        },
        addWishLists: (context, products) => {
            context.commit("ADD_WISHLISTS", products);
        },
        addProduct: (context, product) => {
            context.commit("ADD_PRODUCT", product);
        },
        addProductWishList: (context, product) => {
            context.commit("ADD_WISHLIST", product);
        },
        addSearch(contex, search) {
            contex.commit("ADD_SEARCH", search);
        },
        addProducts(context, products) {
            context.commit("ADD_PRODUCTS", products);
        },
        addItem: (context, product) => {
            context.commit("ADD_ITEM", product);
        },
        removeItem: (context, product) => {
            context.commit("REMOVE_ITEM", product);
        },
        removeProductWishList: (context, product) => {
            context.commit("REMOVE_WISHLIST", product);
        },
        removeProduct: (context, index) => {
            context.commit("REMOVE_PRODUCT", index);
        },
        removeAll(contex) {
            contex.commit("REMOVE_ALL");
        },
        showOrHiddenModal: context => {
            context.commit("SHOW_MODAL");
        },
        showOrHiddenPopupCart: context => {
            context.commit("SHOW_POPUP_CART");
        },
        addClassToast(context, classToast) {
            context.commit("ADDCLASSTOAST", classToast);
        },
        showToast: (context, texto) => {
            context.commit("SHOWTOAST", texto);
        }
    }
});
