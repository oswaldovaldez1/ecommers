<template>
    <footer class="footer bg-gris" v-if="admin == false">
        <b-container>
            <b-row>
                <b-col cols="12" sm="12" md="12" lg="12">
                    <p style="color: white; text-align:right;">
                        tu compra esta protegida por
                        <img
                            src="/img/paypal.png"
                            style="width:72px; height:auto;"
                            alt="openpay"
                        />
                    </p>
                </b-col>

                <b-col cols="6" sm="2" md="3" lg="2">
                    <router-link
                        :to="{ name: 'privacidad' }"
                        class="link-footer f-500"
                        >Aviso de Privacidad</router-link
                    >
                </b-col>
                <b-col cols="6" sm="2" md="3" lg="2">
                    <router-link
                        :to="{ name: 'terminos' }"
                        class="footer-point f-500"
                        >Terminos y condiciones</router-link
                    >
                </b-col>
                <b-col cols="12" sm="8" md="6" lg="8">
                    <p style="color: white;text-align: center;">
                        Copyright © Todos los derechos reservados {{ year }}
                    </p>
                </b-col>
            </b-row>
        </b-container>
    </footer>
</template>

<script>
export default {
    data() {
        return {
            year: 0,
            admin: false
        };
    },
    created() {
        if (this.$route.path.indexOf("admin") > 0) {
            this.admin = true;
        } else {
            this.admin = false;
        }
    },
    mounted() {
        this.year = new Date().getFullYear();
    },
    watch: {
        "$route.path": function(path) {
            if (path.indexOf("admin") > 0) {
                this.admin = true;
            } else {
                this.admin = false;
            }
        }
    }
};
</script>
<style>
.link-footer,
.footer-point {
    color: white !important;
}
.link-footer:hover,
.footer-point:hover,
.link-footer:active,
.footer-point:active {
    color: #ef7968 !important;
}
</style>
