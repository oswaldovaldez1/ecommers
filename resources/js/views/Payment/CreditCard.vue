<template>
    <div class="container">
        <form action>
            <select v-model="tipoPago">
                <option value="0" selected>Tarjeta</option>
                <option value="1" selected>Banco(SPEI)</option>
                <option value="2" selected>Tiendas de Conveniencia</option>
            </select>
            <div class="row margen">
                <div class="col-12 col-sm-12 col-md-5 col-lg-7">
                    <p>$ {{ total }}</p>
                    <div class="card-input">
                        <label for="cardName" class="card-input__label"
                            >NOMBRE</label
                        >
                        <input
                            type="text"
                            id="cardName"
                            class="card-input__input"
                            v-model="cardName"
                            v-on:focus="focusInput"
                            v-on:blur="blurInput"
                            data-ref="cardName"
                            autocomplete="off"
                        />
                    </div>
                    <div class="card-input">
                        <label for="cardLastName" class="card-input__label"
                            >APELLIDOS</label
                        >
                        <input
                            type="text"
                            id="cardLastName"
                            class="card-input__input"
                            v-model="cardLastName"
                        />
                    </div>
                    <div>
                        <label for="direccion">Correo</label>
                        <input type="email" name="correo" v-model="email" />
                    </div>
                    <div>
                        <label for="direccion">Dirección</label>
                        <input
                            type="text"
                            name="direccion"
                            v-model="direccion"
                        />
                    </div>
                    <div>
                        <label for="telefono">Telefono</label>
                        <input type="text" name="telefono" v-model="telefono" />
                    </div>
                    <div>
                        <label for="estado">Estado</label>

                        <select
                            name
                            id
                            v-model="estado"
                            @change="getMunicipio()"
                        >
                            <option
                                v-for="(stado, index) in estados"
                                :key="'estado' + index"
                                :selected="stado === estado"
                                >{{ stado }}</option
                            >
                        </select>
                    </div>
                    <div>
                        <label for="direccion">Municipio</label>
                        <select
                            name
                            id
                            v-model="municipio"
                            @change="getColonia()"
                        >
                            <option
                                v-for="(mun, index) in municipios"
                                :key="'mun' + index"
                                :selected="mun === municipio"
                                >{{ mun }}</option
                            >
                        </select>
                    </div>
                    <div>
                        <label for="direccion">Colonia</label>
                        <select name id v-model="colonia">
                            <option
                                v-for="(col, index) in colonias"
                                :key="'col' + index"
                                :selected="col === colonia"
                                >{{ col }}</option
                            >
                        </select>
                    </div>
                    <div>
                        <label for="direccion">Código Postal</label>
                        <input
                            type="text"
                            name="codigopostal"
                            id
                            v-model="cp"
                            v-on:keyup.enter="getCPInfo"
                        />
                    </div>
                </div>
                <div
                    class="col-12 col-sm-12 col-md-7 col-lg-5"
                    v-if="tipoPago === '0'"
                >
                    <div class="row reversa">
                        <div class="col-12 tarjeta">
                            <div class="card-list">
                                <div
                                    class="card-item"
                                    v-bind:class="{ '-active': isCardFlipped }"
                                >
                                    <div class="card-item__side -front">
                                        <div
                                            class="card-item__focus"
                                            v-bind:class="{
                                                '-active': focusElementStyle
                                            }"
                                            v-bind:style="focusElementStyle"
                                            ref="focusElement"
                                        ></div>
                                        <div class="card-item__cover">
                                            <img
                                                v-bind:src="
                                                    'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                                        currentCardBackground +
                                                        '.jpeg'
                                                "
                                                class="card-item__bg"
                                            />
                                        </div>

                                        <div class="card-item__wrapper">
                                            <div class="card-item__top">
                                                <img
                                                    src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/chip.png"
                                                    class="card-item__chip"
                                                />
                                                <div class="card-item__type">
                                                    <transition
                                                        name="slide-fade-up"
                                                    >
                                                        <img
                                                            v-bind:src="
                                                                'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                                                    getCardType +
                                                                    '.png'
                                                            "
                                                            v-if="getCardType"
                                                            v-bind:key="
                                                                getCardType
                                                            "
                                                            alt
                                                            class="card-item__typeImg"
                                                        />
                                                    </transition>
                                                </div>
                                            </div>
                                            <label
                                                for="cardNumber"
                                                class="card-item__number"
                                                ref="cardNumber"
                                            >
                                                <template
                                                    v-if="
                                                        getCardType === 'amex'
                                                    "
                                                >
                                                    <span
                                                        v-for="(n,
                                                        $index) in amexCardMask"
                                                        :key="$index"
                                                    >
                                                        <transition
                                                            name="slide-fade-up"
                                                        >
                                                            <div
                                                                class="card-item__numberItem"
                                                                v-if="
                                                                    $index >
                                                                        4 &&
                                                                        $index <
                                                                            14 &&
                                                                        cardNumber.length >
                                                                            $index &&
                                                                        n.trim() !==
                                                                            ''
                                                                "
                                                            >
                                                                *
                                                            </div>
                                                            <div
                                                                class="card-item__numberItem"
                                                                :class="{
                                                                    '-active':
                                                                        n.trim() ===
                                                                        ''
                                                                }"
                                                                :key="$index"
                                                                v-else-if="
                                                                    cardNumber.length >
                                                                        $index
                                                                "
                                                            >
                                                                {{
                                                                    cardNumber[
                                                                        $index
                                                                    ]
                                                                }}
                                                            </div>
                                                            <div
                                                                class="card-item__numberItem"
                                                                :class="{
                                                                    '-active':
                                                                        n.trim() ===
                                                                        ''
                                                                }"
                                                                v-else
                                                                :key="
                                                                    $index + 1
                                                                "
                                                            >
                                                                {{ n }}
                                                            </div>
                                                        </transition>
                                                    </span>
                                                </template>

                                                <template v-else>
                                                    <span
                                                        v-for="(n,
                                                        $index) in otherCardMask"
                                                        :key="$index"
                                                        :id="$index + 'span'"
                                                    >
                                                        <transition
                                                            name="slide-fade-up"
                                                        >
                                                            <div
                                                                class="card-item__numberItem"
                                                                :id="
                                                                    $index +
                                                                        '-*'
                                                                "
                                                                v-if="
                                                                    $index >
                                                                        4 &&
                                                                        $index <
                                                                            15 &&
                                                                        cardNumber.length >
                                                                            $index &&
                                                                        n.trim() !==
                                                                            ''
                                                                "
                                                            >
                                                                *
                                                            </div>
                                                            <div
                                                                class="card-item__numberItem"
                                                                :class="{
                                                                    '-active':
                                                                        n.trim() ===
                                                                        ''
                                                                }"
                                                                :key="$index"
                                                                v-else-if="
                                                                    cardNumber.length >
                                                                        $index
                                                                "
                                                                :id="
                                                                    'key' +
                                                                        $index
                                                                "
                                                            >
                                                                {{
                                                                    cardNumber[
                                                                        $index
                                                                    ]
                                                                }}
                                                            </div>
                                                            <div
                                                                class="card-item__numberItem"
                                                                :class="{
                                                                    '-active':
                                                                        n.trim() ===
                                                                        ''
                                                                }"
                                                                v-else
                                                                :id="$index"
                                                                :key="
                                                                    $index + 1
                                                                "
                                                            >
                                                                {{ n }}
                                                            </div>
                                                        </transition>
                                                    </span>
                                                </template>
                                            </label>
                                            <div class="card-item__content">
                                                <label
                                                    for="cardName"
                                                    class="card-item__info"
                                                    ref="cardName"
                                                >
                                                    <div
                                                        class="card-item__holder"
                                                    >
                                                        Nombre
                                                    </div>
                                                    <transition
                                                        name="slide-fade-up"
                                                    >
                                                        <div
                                                            class="card-item__name"
                                                            v-if="
                                                                getCardAllName.length
                                                            "
                                                            key="1"
                                                        >
                                                            <transition-group
                                                                name="slide-fade-right"
                                                            >
                                                                <span
                                                                    class="card-item__nameItem"
                                                                    v-for="(n,
                                                                    $index) in getCardAllName.replace(
                                                                        /\s\s+/g,
                                                                        ' '
                                                                    )"
                                                                    v-if="
                                                                        $index ===
                                                                            $index
                                                                    "
                                                                    v-bind:key="
                                                                        $index +
                                                                            1
                                                                    "
                                                                >
                                                                    {{ n }}
                                                                </span>
                                                            </transition-group>
                                                        </div>
                                                        <div
                                                            class="card-item__name"
                                                            v-else
                                                            key="2"
                                                        >
                                                            Nombre
                                                        </div>
                                                    </transition>
                                                </label>
                                                <label
                                                    class="card-item__date"
                                                    ref="cardDate"
                                                >
                                                    <div
                                                        for="cardMonth"
                                                        class="card-item__dateTitle"
                                                    >
                                                        Expiración
                                                    </div>
                                                    <div
                                                        for="cardMonth"
                                                        class="card-item__dateItem"
                                                    >
                                                        <transition
                                                            name="slide-fade-up"
                                                        >
                                                            <span
                                                                v-if="cardMonth"
                                                                v-bind:key="
                                                                    cardMonth
                                                                "
                                                            >
                                                                {{ cardMonth }}
                                                            </span>
                                                            <span v-else key="2"
                                                                >MM</span
                                                            >
                                                        </transition>
                                                    </div>
                                                    /
                                                    <div
                                                        for="cardYear"
                                                        class="card-item__dateItem"
                                                    >
                                                        <transition
                                                            name="slide-fade-up"
                                                        >
                                                            <span
                                                                v-if="cardYear"
                                                                v-bind:key="
                                                                    cardYear
                                                                "
                                                            >
                                                                {{
                                                                    String(
                                                                        cardYear
                                                                    ).slice(
                                                                        2,
                                                                        4
                                                                    )
                                                                }}
                                                            </span>
                                                            <span v-else key="2"
                                                                >YY</span
                                                            >
                                                        </transition>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-item__side -back">
                                        <div class="card-item__cover">
                                            <img
                                                v-bind:src="
                                                    'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                                        currentCardBackground +
                                                        '.jpeg'
                                                "
                                                class="card-item__bg"
                                            />
                                        </div>
                                        <div class="card-item__band"></div>
                                        <div class="card-item__cvv">
                                            <div class="card-item__cvvTitle">
                                                CVV
                                            </div>
                                            <div class="card-item__cvvBand">
                                                <span
                                                    v-for="(n,
                                                    $index) in cardCvv"
                                                    :key="$index"
                                                    >*</span
                                                >
                                            </div>
                                            <div class="card-item__type">
                                                <img
                                                    v-bind:src="
                                                        'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/' +
                                                            getCardType +
                                                            '.png'
                                                    "
                                                    v-if="getCardType"
                                                    class="card-item__typeImg"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card-input">
                                <label
                                    for="cardNumber"
                                    class="card-input__label"
                                    >Numero de Tarjeta</label
                                >
                                <input
                                    type="text"
                                    id="cardNumber"
                                    class="card-input__input"
                                    v-mask="generateCardNumberMask"
                                    v-model="cardNumber"
                                    v-on:focus="focusInput"
                                    v-on:blur="blurInput"
                                    data-ref="cardNumber"
                                    autocomplete="off"
                                />
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="card-input__label">Mes</label>
                                    <select
                                        class="card-input__input -select"
                                        id="cardMonth"
                                        v-model="cardMonth"
                                        v-on:focus="focusInput"
                                        v-on:blur="blurInput"
                                        data-ref="cardDate"
                                    >
                                        <option value disabled selected
                                            >Mes</option
                                        >
                                        <option
                                            v-bind:value="n < 10 ? '0' + n : n"
                                            v-for="n in 12"
                                            v-bind:disabled="n < minCardMonth"
                                            v-bind:key="n"
                                            >{{ n < 10 ? "0" + n : n }}</option
                                        >
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="card-input__label">Año</label>
                                    <select
                                        class="card-input__input -select"
                                        id="cardYear"
                                        v-model="cardYear"
                                        v-on:focus="focusInput"
                                        v-on:blur="blurInput"
                                        data-ref="cardDate"
                                    >
                                        <option value disabled selected
                                            >Año</option
                                        >
                                        <option
                                            v-bind:value="$index + minCardYear"
                                            v-for="(n, $index) in 12"
                                            v-bind:key="n"
                                            >{{ $index + minCardYear }}</option
                                        >
                                    </select>
                                </div>
                                <div class="col-4">
                                    <div class="card-form__col -cvv">
                                        <div class="card-input">
                                            <label
                                                for="cardCvv"
                                                class="card-input__label"
                                                >CVV</label
                                            >
                                            <input
                                                type="text"
                                                class="card-input__input"
                                                id="cardCvv"
                                                v-mask="'####'"
                                                maxlength="4"
                                                v-model="cardCvv"
                                                v-on:focus="flipCard(true)"
                                                v-on:blur="flipCard(false)"
                                                autocomplete="off"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" @click="pagar" class="btn btn-success">
                Pagar
            </button>
        </form>
        <!--com>
        <div class="card-form__inner">





                </div>

                <button class="card-form__button">
                    Submit
                </button>
            </div>
    <com-->
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
    data() {
        return {
            currentCardBackground: Math.floor(Math.random() * 25 + 1), // just for fun :D
            cardName: "",
            tipoPago: "0",
            cardLastName: "",
            cardNumber: "",
            cardMonth: "",
            cardYear: "",
            cardCvv: "",
            minCardYear: new Date().getFullYear(),
            amexCardMask: "#### ###### #####",
            otherCardMask: "#### #### #### ####",
            cardNumberTemp: "",
            isCardFlipped: false,
            focusElementStyle: null,
            isInputFocused: false,
            estado: "",
            municipio: "",
            colonia: "",
            cp: "",
            direccion: "",
            telefono: "",
            email: "",
            estados: [],
            municipios: [],
            colonias: [],
            openpay_id: "m1mblflmqxumoc3tu6ln",
            openpay_key: "pk_5c4e49d935764b56b7de68ea767b2657",
            openpay_sandbox_mode: true,
            deviceSessionId: "",
            token: ""
        };
    },
    mounted() {
        this.cardNumberTemp = this.otherCardMask;
        document.getElementById("cardNumber").focus();
        this.getEstados();
        OpenPay.setId(this.openpay_id);
        OpenPay.setApiKey(this.openpay_key);
        OpenPay.setSandboxMode(this.openpay_sandbox_mode);
        this.deviceSessionId = OpenPay.deviceData.setup();
    },
    computed: {
        ...mapGetters(["total", "getProductInCart"]),
        getCardAllName() {
            return this.cardName + " " + this.cardLastName;
        },
        getCardType() {
            let number = this.cardNumber;
            let re = new RegExp("^4");
            if (number.match(re) != null) return "visa";

            re = new RegExp("^(34|37)");
            if (number.match(re) != null) return "amex";

            re = new RegExp("^5[1-5]");
            if (number.match(re) != null) return "mastercard";

            re = new RegExp("^6011");
            if (number.match(re) != null) return "discover";

            re = new RegExp("^9792");
            if (number.match(re) != null) return "troy";

            return "visa"; // default type
        },
        generateCardNumberMask() {
            return this.getCardType === "amex"
                ? this.amexCardMask
                : this.otherCardMask;
        },
        minCardMonth() {
            if (this.cardYear === this.minCardYear)
                return new Date().getMonth() + 1;
            return 1;
        }
    },
    watch: {
        cardYear() {
            if (this.cardMonth < this.minCardMonth) {
                this.cardMonth = "";
            }
        }
    },
    methods: {
        ...mapActions(["addClassToast", "showToast", "removeAll"]),
        pagar() {
            /*window.open(
        "https://sandbox-dashboard.openpay.mx/paynet-pdf/m1mblflmqxumoc3tu6ln/1010102650005829",
        "_blank"
      );
      this.downloadWithAxios();
      return;*/
            switch (this.tipoPago) {
                case "0": {
                    if (
                        !OpenPay.card.validateCardNumber(
                            this.cardNumber.replace(/ /gi, "")
                        )
                    ) {
                        this.addClassToast("alert toasterror");
                        this.showToast("Tarjeta no valida");
                        return;
                    }
                    if (
                        !OpenPay.card.validateCVC(
                            this.cardCvv,
                            this.cardNumber.replace(/ /gi, "")
                        )
                    ) {
                        //this.showToast("ccv no es valido con su numero de tarjeta");
                        this.addClassToast("alert toasterror");
                        this.showToast(
                            "ccv no es valido con su numero de tarjeta"
                        );
                        return;
                    }
                    /*      console.log(
        OpenPay.card.validateExpiry(
          String(this.cardMonth),
          String(this.cardYear)
        )
      );

      console.log(OpenPay.card.cardType(this.cardNumber.replace(/ /gi, "")));*/

                    OpenPay.token.create(
                        {
                            card_number: this.cardNumber.replace(/ /gi, ""),
                            holder_name: this.getCardAllName.toUpperCase(),
                            expiration_year: String(this.cardYear).slice(2, 4),
                            expiration_month: this.cardMonth,
                            cvv2: this.cardCvv,
                            address: {
                                city: this.municipio,
                                line3: this.estado,
                                postal_code: this.cp,
                                line1: this.direccion.toUpperCase(),
                                line2: this.colonia,
                                state: this.estado,
                                country_code: "MX"
                            }
                        },
                        response => {
                            console.log(response.data);
                            this.token = response.data.id;
                            this.postPagar();
                        },
                        onError => {
                            console.log(onError);
                            return;
                        }
                    );
                    break;
                }
                case "1": {
                    this.postPagar();
                    break;
                }
                case "2": {
                    this.postPagar();
                    break;
                }
            }
        },
        postPagar() {
            var metodoPago = parseInt(this.tipoPago);
            this.axios
                .post(this.$GlobalUrl + "/api/charge", {
                    firstname: this.cardName.toUpperCase(),
                    secondname: this.cardLastName.toUpperCase(),
                    email: this.email,
                    address: this.direccion,
                    city: this.municipio,
                    state: this.estado,
                    zip: this.cp,
                    col: this.colonia,
                    mount: this.total.replace(",", ""),
                    cardname: this.getCardAllName.toUpperCase(),
                    cardnumber: this.cardNumber.replace(/ /gi, ""),
                    expmonth: this.cardMonth,
                    expyear: String(this.cardYear).slice(2, 4),
                    cvv: this.cvv,
                    metodo: metodoPago,
                    phone: this.telefono,
                    token_id: this.token,
                    deviceIdHiddenFieldName: this.deviceSessionId,
                    products: JSON.stringify(this.getProductInCart)
                })
                .then(response => {
                    console.log(response.data);
                    this.removeAll();
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            //api/charge
        },
        flipCard(status) {
            this.isCardFlipped = status;
        },
        focusInput(e) {
            if (this.tipoPago === "0") {
                this.isInputFocused = true;
                let targetRef = e.target.dataset.ref;
                let target = this.$refs[targetRef];
                this.focusElementStyle = {
                    width: `${target.offsetWidth}px`,
                    height: `${target.offsetHeight}px`,
                    transform: `translateX(${target.offsetLeft}px) translateY(${target.offsetTop}px)`
                };
            }
        },
        blurInput() {
            let vm = this;
            setTimeout(() => {
                if (!vm.isInputFocused) {
                    vm.focusElementStyle = null;
                }
            }, 300);
            vm.isInputFocused = false;
        },
        getCPInfo() {
            let getUrl =
                "https://api-sepomex.hckdrk.mx/query/info_cp/" + this.cp;

            this.axios
                .get(getUrl)
                .then(response => {
                    console.log(response.data);

                    this.estado = response.data[0].response.estado;
                    this.municipio = response.data[0].response.municipio;
                    this.getColoniaCP();
                    this.getEstados();
                    this.getMunicipio();
                    this.getColonia();
                })
                .catch(error => {
                    console.log(error.response.data.error_message);
                    this.municipios = [];
                    this.municipio = "";
                    this.colonias = [];
                    this.colonia = "";
                    this.estado = "";
                    this.getEstados();
                });
        },

        getColoniaCP() {
            let getUrl =
                "https://api-sepomex.hckdrk.mx/query/get_colonia_por_cp/" +
                this.cp;

            this.axios
                .get(getUrl)
                .then(response => {
                    if ((response.data.response.colonia.length = 1)) {
                        this.colonia = response.data.response.colonia[0];
                    }
                })
                .catch(error => {});
        },
        getEstados() {
            let getUrl = "https://api-sepomex.hckdrk.mx/query/get_estados";
            this.estados = [];
            this.axios
                .get(getUrl)
                .then(response => {
                    //console.log(response.data.response.estado);
                    this.estados = response.data.response.estado;
                    this.estados.sort();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getMunicipio() {
            let getUrl =
                "https://api-sepomex.hckdrk.mx/query/get_municipio_por_estado/" +
                this.estado;
            this.municipios = [];
            this.axios
                .get(getUrl)
                .then(response => {
                    this.municipios = response.data.response.municipios;
                    this.municipios.sort();
                })
                .catch(error => {});
        },
        getColonia() {
            let getUrl =
                "https://api-sepomex.hckdrk.mx/query/get_colonia_por_municipio/" +
                this.municipio;
            this.colonias = [];
            this.axios
                .get(getUrl)
                .then(response => {
                    this.colonias = response.data.response.colonia;
                    this.colonias.sort();
                })
                .catch(error => {});
        }
    }
};
</script>
<style lang="scss">
@import url("https://fonts.googleapis.com/css?family=Source+Code+Pro:400,500,600,700|Source+Sans+Pro:400,600,700&display=swap");

.tarjeta {
    height: 300px;
}
.card-item {
    max-width: 430px;
    height: 270px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
    z-index: 2;
    width: 100%;

    @media screen and (max-width: 480px) {
        max-width: 310px;
        height: 220px;
        width: 90%;
    }

    @media screen and (max-width: 360px) {
        height: 180px;
    }

    &.-active {
        .card-item__side {
            &.-front {
                transform: perspective(1000px) rotateY(180deg) rotateX(0deg)
                    rotateZ(0deg);
            }
            &.-back {
                transform: perspective(1000px) rotateY(0) rotateX(0deg)
                    rotateZ(0deg);
                /* box-shadow: 0 20px 50px 0 rgba(81, 88, 206, 0.65);*/
            }
        }
    }

    &__focus {
        position: absolute;
        z-index: 3;
        border-radius: 5px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);
        opacity: 0;
        pointer-events: none;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.65);

        &:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: rgb(8, 20, 47);
            height: 100%;
            border-radius: 5px;
            filter: blur(25px);
            opacity: 0.5;
        }

        &.-active {
            opacity: 1;
        }
    }

    &__side {
        border-radius: 15px;
        overflow: hidden;
        /* box-shadow: 3px 13px 30px 0px rgba(11, 19, 41, 0.5);*/
        box-shadow: 0 20px 60px 0 rgba(14, 42, 90, 0.55);
        transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
        transform-style: preserve-3d;
        transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
        backface-visibility: hidden;
        height: 100%;

        &.-back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            transform: perspective(2000px) rotateY(-180deg) rotateX(0deg)
                rotate(0deg);
            z-index: 2;
            padding: 0;
            /* background-color: #2364d2;
             background-image: linear-gradient(
               43deg,
               #4158d0 0%,
               #8555c7 46%,
               #2364d2 100%
             );*/
            height: 100%;

            .card-item__cover {
                transform: rotateY(-180deg);
            }
        }
    }
    &__bg {
        max-width: 100%;
        display: block;
        max-height: 100%;
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    &__cover {
        height: 100%;
        background-color: #1c1d27;
        position: absolute;
        height: 100%;
        background-color: #1c1d27;
        left: 0;
        top: 0;
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        &:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(6, 2, 29, 0.45);
        }
    }

    &__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 40px;
        padding: 0 10px;

        @media screen and (max-width: 480px) {
            margin-bottom: 25px;
        }
        @media screen and (max-width: 360px) {
            margin-bottom: 15px;
        }
    }

    &__chip {
        width: 60px;
        @media screen and (max-width: 480px) {
            width: 50px;
        }
        @media screen and (max-width: 360px) {
            width: 40px;
        }
    }

    &__type {
        height: 45px;
        position: relative;
        display: flex;
        justify-content: flex-end;
        max-width: 100px;
        margin-left: auto;
        width: 100%;

        @media screen and (max-width: 480px) {
            height: 40px;
            max-width: 90px;
        }
        @media screen and (max-width: 360px) {
            height: 30px;
        }
    }

    &__typeImg {
        max-width: 100%;
        object-fit: contain;
        max-height: 100%;
        object-position: top right;
    }

    &__info {
        color: #fff;
        width: 100%;
        max-width: calc(100% - 85px);
        padding: 10px 15px;
        font-weight: 500;
        display: block;

        cursor: pointer;

        @media screen and (max-width: 480px) {
            padding: 10px;
        }
    }

    &__holder {
        opacity: 0.7;
        font-size: 13px;
        margin-bottom: 6px;
        @media screen and (max-width: 480px) {
            font-size: 12px;
            margin-bottom: 5px;
        }
    }

    &__wrapper {
        font-family: "Source Code Pro", monospace;
        padding: 25px 15px;
        position: relative;
        z-index: 4;
        height: 100%;
        text-shadow: 7px 6px 10px rgba(14, 42, 90, 0.8);
        user-select: none;
        @media screen and (max-width: 480px) {
            padding: 20px 10px;
        }
    }

    &__name {
        font-size: 18px;
        line-height: 1;
        white-space: nowrap;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: uppercase;
        @media screen and (max-width: 480px) {
            font-size: 16px;
        }
    }
    &__nameItem {
        display: inline-block;
        min-width: 8px;
        position: relative;
    }

    &__number {
        font-weight: 500;
        line-height: 1;
        color: #fff;
        font-size: 27px;
        margin-bottom: 35px;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;

        @media screen and (max-width: 480px) {
            font-size: 21px;
            margin-bottom: 15px;
            padding: 10px 10px;
        }

        @media screen and (max-width: 360px) {
            font-size: 19px;
            margin-bottom: 10px;
            padding: 10px 10px;
        }
    }

    &__numberItem {
        width: 15px;
        display: inline-block;
        &.-active {
            width: 30px;
        }

        @media screen and (max-width: 480px) {
            width: 13px;

            &.-active {
                width: 16px;
            }
        }

        @media screen and (max-width: 360px) {
            width: 12px;

            &.-active {
                width: 8px;
            }
        }
    }

    &__content {
        color: #fff;
        display: flex;
        align-items: flex-start;
    }

    &__date {
        flex-wrap: wrap;
        font-size: 18px;
        margin-left: auto;
        padding: 10px;
        display: inline-flex;
        width: 80px;
        white-space: nowrap;
        flex-shrink: 0;
        cursor: pointer;
        height: 65px;
        @media screen and (max-width: 480px) {
            font-size: 16px;
        }
    }

    &__dateItem {
        position: relative;
        span {
            width: 22px;
            display: inline-block;
        }
    }

    &__dateTitle {
        opacity: 0.7;
        font-size: 13px;
        padding-bottom: 6px;
        width: 100%;

        @media screen and (max-width: 480px) {
            font-size: 12px;
            padding-bottom: 5px;
        }
    }
    &__band {
        background: rgba(0, 0, 19, 0.8);
        width: 100%;
        height: 50px;
        margin-top: 30px;
        position: relative;
        z-index: 2;
        @media screen and (max-width: 480px) {
            margin-top: 20px;
        }
        @media screen and (max-width: 360px) {
            height: 40px;
            margin-top: 10px;
        }
    }

    &__cvv {
        text-align: right;
        position: relative;
        z-index: 2;
        padding: 15px;
        .card-item__type {
            opacity: 0.7;
        }

        @media screen and (max-width: 360px) {
            padding: 10px 15px;
        }
    }
    &__cvvTitle {
        padding-right: 10px;
        font-size: 15px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 5px;
    }
    &__cvvBand {
        height: 45px;
        background: #fff;
        margin-bottom: 30px;
        text-align: right;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
        color: #1a3b5d;
        font-size: 18px;
        border-radius: 4px;
        box-shadow: 0px 10px 20px -7px rgba(32, 56, 117, 0.35);

        @media screen and (max-width: 480px) {
            height: 40px;
            margin-bottom: 20px;
        }

        @media screen and (max-width: 360px) {
            margin-bottom: 15px;
        }
    }
}

.card-list {
    margin-bottom: -130px;

    @media screen and (max-width: 480px) {
        margin-bottom: -120px;
    }
}

.card-input {
    margin-bottom: 20px;
    &__label {
        font-size: 14px;
        margin-bottom: 5px;
        font-weight: 500;
        color: #1a3b5d;
        width: 100%;
        display: block;
        user-select: none;
    }
    &__input {
        width: 100%;
        height: 50px;
        border-radius: 5px;
        box-shadow: none;
        border: 1px solid #ced6e0;
        transition: all 0.3s ease-in-out;
        font-size: 18px;
        padding: 5px 15px;
        background: none;
        color: #1a3b5d;
        font-family: "Source Sans Pro", sans-serif;

        &:hover,
        &:focus {
            border-color: #3d9cff;
        }

        &:focus {
            box-shadow: 0px 10px 20px -13px rgba(32, 56, 117, 0.35);
        }
        &.-select {
            -webkit-appearance: none;
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUxJREFUeNrM1sEJwkAQBdCsngXPHsQO9O5FS7AAMVYgdqAd2IGCDWgFnryLFQiCZ8EGnJUNimiyM/tnk4HNEAg/8y6ZmMRVqz9eUJvRaSbvutCZ347bXVJy/ZnvTmdJ862Me+hAbZCTs6GHpyUi1tTSvPnqTpoWZPUa7W7ncT3vK4h4zVejy8QzM3WhVUO8ykI6jOxoGA4ig3BLHcNFSCGqGAkig2yqgpEiMsjSfY9LxYQg7L6r0X6wS29YJiYQYecemY+wHrXD1+bklGhpAhBDeu/JfIVGxaAQ9sb8CI+CQSJ+QmJg0Ii/EE2MBiIXooHRQhRCkBhNhBcEhLkwf05ZCG8ICCOpk0MULmvDSY2M8UawIRExLIQIEgHDRoghihgRIgiigBEjgiFATBACAgFgghEwSAAGgoBCBBgYAg5hYKAIFYgHBo6w9RRgAFfy160QuV8NAAAAAElFTkSuQmCC");
            background-size: 12px;
            background-position: 90% center;
            background-repeat: no-repeat;
            padding-right: 30px;
        }
    }
}

.slide-fade-up-enter-active {
    transition: all 0.25s ease-in-out;
    transition-delay: 0.1s;
    position: relative;
}
.slide-fade-up-leave-active {
    transition: all 0.25s ease-in-out;
    position: absolute;
}
.slide-fade-up-enter {
    opacity: 0;
    transform: translateY(15px);
    pointer-events: none;
}
.slide-fade-up-leave-to {
    opacity: 0;
    transform: translateY(-15px);
    pointer-events: none;
}

.slide-fade-right-enter-active {
    transition: all 0.25s ease-in-out;
    transition-delay: 0.1s;
    position: relative;
}
.slide-fade-right-leave-active {
    transition: all 0.25s ease-in-out;
    position: absolute;
}
.slide-fade-right-enter {
    opacity: 0;
    transform: translateX(10px) rotate(45deg);
    pointer-events: none;
}
.slide-fade-right-leave-to {
    opacity: 0;
    transform: translateX(-10px) rotate(45deg);
    pointer-events: none;
}

.github-btn {
    position: absolute;
    right: 40px;
    bottom: 50px;
    text-decoration: none;
    padding: 15px 25px;
    border-radius: 4px;
    box-shadow: 0px 4px 30px -6px rgba(36, 52, 70, 0.65);
    background: #24292e;
    color: #fff;
    font-weight: bold;
    letter-spacing: 1px;
    font-size: 16px;
    text-align: center;
    transition: all 0.3s ease-in-out;

    @media screen and (min-width: 500px) {
        &:hover {
            transform: scale(1.1);
            box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
        }
    }

    @media screen and (max-width: 700px) {
        position: relative;
        bottom: auto;
        right: auto;
        margin-top: 20px;

        &:active {
            transform: scale(1.1);
            box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
        }
    }
}
.reversa {
    @media screen and (max-width: 500px) {
        & {
            display: flex;
            flex-direction: column-reverse;
        }
    }
}
.margen {
    @media screen and (max-width: 500px) {
        & {
            margin-bottom: 150px;
        }
    }
}
</style>
