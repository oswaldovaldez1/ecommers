<template>
    <div v-if="!admin">
        <v-app id="inspire">
            <div class="text-xs-center">
                <v-menu
                    min-width="300"
                    top
                    v-model="menu"
                    :close-on-click="false"
                    :close-on-content-click="false"
                    :nudge-top="60"
                    offset-x
                >
                    <template v-slot:activator="{ on }">
                        <transition name="fade" mode="out-in">
                            <button
                                class="btn-whats"
                                v-model="fab"
                                v-on="on"
                                color="green darken-1"
                                @click="
                                    fab = !fab;
                                    menu = false;
                                "
                                dark
                                fixed
                                bottom
                                right
                                fab
                            >
                                <i
                                    class="fab fa-whatsapp icon-white"
                                    v-if="!fab"
                                ></i>
                                <v-icon class="icon-white" v-if="fab"
                                    >close</v-icon
                                >
                            </button>
                        </transition>
                    </template>
                    <v-card>
                        <v-list dark class="chat-header">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        <img
                                            class="svg-icon"
                                            src="/img/Lanesalogo.svg"
                                            type="image/svg"
                                        />
                                        Lanesa1957
                                    </v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-btn
                                        icon
                                        @click="
                                            fab = !fab;
                                            menu = false;
                                        "
                                    >
                                        <v-icon>cancel</v-icon>
                                    </v-btn>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <v-divider></v-divider>
                        <v-container class="chat-background">
                            <v-layout row wrap>
                                <v-flex class="text-xs-right" xs12 mb-4>
                                    <v-chip class="chip-chat">
                                        Hola, ¿me puede ayudar?
                                        <v-icon right>done_all</v-icon>
                                    </v-chip>
                                </v-flex>
                                <v-flex class="text-xs-left" xs12 mb-4>
                                    <v-chip
                                        class="chip-chat"
                                        style="background-color: while !important;"
                                        >Hola, ¿en que le puede ayudar?</v-chip
                                    >
                                </v-flex>
                                <v-flex xs10>
                                    <v-text-field
                                        single-line
                                        color="transparent"
                                        class="my-input"
                                        v-model="text"
                                        outline
                                        @keyup.enter="openLink()"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn
                                        large
                                        class="teal--text"
                                        icon
                                        @click="
                                            fab = !fab;
                                            menu = false;
                                        "
                                        :href="apilink"
                                        target="_blank"
                                    >
                                        <v-icon large>send</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-menu>
            </div>
        </v-app>
    </div>
</template>

<script>
export default {
    data: () => ({
        fab: false,
        fav: true,
        menu: false,
        message: false,
        hints: true,
        phone: "529511779314",
        text: "",
        apilink: "",
        admin: false
    }),
    watch: {
        text(val) {
            this.apilink = "http://";
            this.apilink += this.isMobile() ? "api" : "web";
            this.apilink +=
                ".whatsapp.com/send?phone=" +
                this.phone +
                "&text=" +
                encodeURI(this.text);
        },
        "$route.path": function(path) {
            if (path.indexOf("admin") > 0) {
                this.admin = true;
            } else {
                this.admin = false;
            }
        }
    },
    created: function() {
        this.apilink = "http://";
        this.apilink += this.isMobile() ? "api" : "web";
        this.apilink +=
            ".whatsapp.com/send?phone=" +
            this.phone +
            "&text=" +
            encodeURI("Hello!");
        if (this.$route.path.indexOf("admin") > 0) {
            this.admin = true;
        } else {
            this.admin = false;
        }
    },
    methods: {
        openLink() {
            global.open(this.apilink, "_blank");
        },
        isMobile() {
            var check = false;
            (function(a) {
                if (
                    /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
                        a
                    ) ||
                    /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
                        a.substr(0, 4)
                    )
                )
                    check = true;
            })(navigator.userAgent || navigator.vendor || window.opera);
            return check;
        }
    }
};
</script>

<style>
.text-xs-left span.v-chip__content {
    background-color: white;
}
.my-input.v-input .v-input__slot {
    border-radius: 100px;
    border: 1px solid rgba(0, 0, 0, 0.54);
    padding: 0px 10px;
    background-color: white;
}

.v-text-field > .v-input__control > .v-input__slot:before {
    border-style: none;
    border-width: thin 0 0;
}

.v-text-field {
    padding-top: 0px !important;
}
.chat-background {
    background-color: #ece5dd !important;
}
.chat-header {
    background-color: #075e54 !important;
}
.chip-chat {
    background-color: #dcf8c6 !important;
}
div.v-btn__content {
    width: 100%;
    position: initial !important;
}

div.v-btn__content i.v-icon.material-icons.theme--dark {
    /*top: -12px !important;*/
    font-size: 30px;
}

i.v-icon.v-icon--right.material-icons.theme--light {
    color: darkgreen !important;
}
.svg-icon {
    width: 24px;
    height: 24px;
    border-radius: 100%;
    border: 1px solid;
    left: 0px;
    background-color: white;
}
.btn-whats {
    width: 50px;
    background-color: #43a047 !important;
    border-color: #43a047 !important;
    will-change: box-shadow;
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2),
        0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
    cursor: pointer;
    position: fixed;
    bottom: 50px;
    right: 16px;
    height: 50px;
    text-align: center;
    border-radius: 100%;
    align-content: center;
    color: white;
    z-index: 99;
}
.icon-white {
    color: white !important;
    font-size: 24px;
}
.v-menu__content {
    position: fixed !important;
}
.v-list-item {
    align-items: center;
    color: inherit;
    display: flex;
    font-size: 16px;
    font-weight: 400;
    height: 48px;
    margin: 0;
    padding: 0 16px;
    position: relative;
    text-decoration: none;
    transition: background 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}
.v-list-item__action {
    display: flex;
    justify-content: flex-start;
    min-width: 56px;
    justify-content: flex-end;
}
.v-list-item__content {
    text-align: left;
    flex: 1 1 auto;
    overflow: hidden;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-direction: column;
}
.v-menu__content {
    max-width: 300px !important;
}
</style>
