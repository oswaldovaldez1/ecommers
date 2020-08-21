<template>
    <div class="container">
        <div class="row">
            <div class="col-4" style="border: 4px double black;">
                Nombre:
                <br />
                {{ nombre }}
            </div>
            <div class="col-4" style="border: 4px double black;">
                Telefono:
                <br />
                {{ telefono }}
            </div>
            <div class="col-4" style="border: 4px double black;">
                Correo:
                <br />
                {{ correo }}
            </div>
            <div class="col-4" style="border: 4px double black;">
                Dirección:
                <br />
                {{ direccion }}
            </div>
            <div class="col-3" style="border: 4px double black;">
                Estado:
                <br />
                {{ estado }}
            </div>
            <div class="col-3" style="border: 4px double black;">
                Municipio:
                <br />
                {{ municipio }}
            </div>
            <div class="col-2" style="border: 4px double black;">
                Codigo postal:
                <br />
                {{ cp }}
            </div>
        </div>
        <div class="row">
            <div class="col-12" style="border: 4px double black;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">detalle</th>
                            <th scope="col">cantidad</th>
                            <th scope="col">Propietario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(dato, index) in detallePedido" :key="index">
                            <td scope="row">{{ index + 1 }}</td>
                            <th>{{ dato.titulo }}</th>
                            <td>{{ dato.qty }}</td>
                            <td>{{ dato.propietario }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["datos"],
    data() {
        return {
            nombre: "",
            direccion: "",
            estado: "",
            municipio: "",
            cp: "",
            telefono: "",
            correo: "",
            detallePedido: {}
        };
    },
    watch: {
        $props: {
            handler() {
                this.nombre = this.datos.customer_name;
                this.direccion = this.datos.customer_addr;
                this.estado = this.datos.customer_edo;
                this.municipio = this.datos.customer_city;
                this.cp = this.datos.customer_zip;
                this.telefono = this.datos.customer_phone;
                this.correo = this.datos.customer_email;
                this.getData();
            },
            deep: true,
            immediate: true
        }
    },
    mounted() {
        this.nombre = this.datos.customer_name;
        this.direccion = this.datos.customer_addr;
        this.estado = this.datos.customer_edo;
        this.municipio = this.datos.customer_city;
        this.cp = this.datos.customer_zip;
        this.telefono = this.datos.customer_phone;
        this.getData();
    },
    methods: {
        getData() {
            this.axios
                .get(this.$GlobalUrl + "/api/orden/detalle/" + this.datos.id)
                .then(response => {
                    this.detallePedido = response.data.detalle;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>

<style></style>
