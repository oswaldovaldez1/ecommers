<template>
    <div>
        <carousel
            v-if="getImg.length > 0"
            :autoplay="true"
            :items="1"
            :nav="false"
            :loop="true"
            :dots="false"
        >
            <div
                v-for="(img, index) in getImg"
                :key="index"
                class="item"
                style="width:100%"
            >
                <div
                    class="section"
                    :style="{ 'background-image': 'url(' + img.url + ')' }"
                ></div>
            </div>
        </carousel>
    </div>
</template>

<script>
import carousel from "vue-owl-carousel";
import { mapGetters } from "vuex";
export default {
    data() {
        return {};
    },
    computed: {
        ...mapGetters(["getSlider"]),
        getImg() {
            if (typeof this.getSlider === "undefined") return [];
            else return JSON.parse(this.getSlider);
        }
    },
    components: {
        carousel
    }
};
</script>

<style>
.section {
    height: 400px;
    width: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
.owl-carousel .owl-item {
    margin: 0px;
    padding: 0px;
}
</style>
