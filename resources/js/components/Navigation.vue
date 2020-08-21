<template>
    <div>
        <NavHome v-if="admin == false"></NavHome>
        <NavAdmin v-if="admin"></NavAdmin>
    </div>
</template>

<script>
import NavHome from "../views/navegador/NavHome";
import NavAdmin from "../views/navegador/NavAdmin";
import { mapActions } from "vuex";
export default {
    data() {
        return {
            admin: false
        };
    },
    components: {
        NavHome,
        NavAdmin
    },
    created() {
        if (this.$route.path.indexOf("admin") > 0) {
            this.admin = true;

            //document.querySelector("#whatsapbtn").classList.remove("d-none");
        } else {
            this.admin = false;
            //document.querySelector("#whatsapbtn").classList.add("d-none");
        }
        this.getconfig();
    },
    mounted() {},
    watch: {
        "$route.path": function(path) {
            if (path.indexOf("admin") > 0) {
                this.admin = true;
                //document.querySelector("#whatsapbtn").classList.remove("d-none");
            } else {
                this.admin = false;
                //document.querySelector("#whatsapbtn").classList.add("d-none");
            }
            this.getconfig();
        }
    },
    methods: {
        ...mapActions(["updateconfig"]),
        getconfig() {
            this.axios
                .get(this.$GlobalUrl + "/api/config")
                .then(response => {
                    this.updateconfig(response.data[0]);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
