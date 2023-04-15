<template>
    <main id="content" role="main">
            <!-- Slider Section -->
            <div class="mb-5">
                <div class="bg-img-hero" style="background-image: url(../../assets/img/1920X422/img1.jpg);">
                    <div class="container min-height-420 overflow-hidden">
                        <div class="js-slick-carousel u-slick"
                            data-pagi-classes="text-center position-absolute right-0 bottom-0 left-0 u-slick__pagination u-slick__pagination--long justify-content-start mb-3 mb-md-4 offset-xl-3 pl-2 pb-1">
                            <div class="js-slide bg-img-hero-center">
                                <div class="row min-height-420 py-7 py-md-0">
                                    <div class="offset-xl-3 col-xl-4 col-6 mt-md-8">
                                        <h1 class="font-size-64 text-lh-57 font-weight-light"
                                            data-scs-animation-in="fadeInUp">
                                            THE NEW <span class="d-block font-size-55">STANDARD</span>
                                        </h1>
                                        <h6 class="font-size-15 font-weight-bold mb-3"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="200">UNDER FAVORABLE SMARTWATCHES
                                        </h6>
                                        <div class="mb-4"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="300">
                                            <span class="font-size-13">FROM</span>
                                            <div class="font-size-50 font-weight-bold text-lh-45">
                                                <sup class="">$</sup>749<sup class="">99</sup>
                                            </div>
                                        </div>
                                        <a href="../shop/single-product-fullwidth.html" class="btn btn-primary transition-3d-hover rounded-lg font-weight-normal py-2 px-md-7 px-3 font-size-16"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="400">
                                            Start Buying
                                        </a>
                                    </div>
                                    <div class="col-xl-5 col-6  d-flex align-items-center"
                                        data-scs-animation-in="zoomIn"
                                        data-scs-animation-delay="500">
                                        <img class="img-fluid" src="https://stackcodes.net/html/madras_themes/electro/assets/img/416X420/img1.png" alt="Image Description">
                                    </div>
                                </div>
                            </div>
                            <div class="js-slide bg-img-hero-center" data-animation-delay="0">
                                <div class="row min-height-420 py-7 py-md-0">
                                    <div class="offset-xl-3 col-xl-4 col-6 mt-md-8">
                                        <h1 class="font-size-64 text-lh-57 font-weight-light"
                                            data-scs-animation-in="fadeInUp">
                                            THE NEW <span class="d-block font-size-55">STANDARD</span>
                                        </h1>
                                        <h6 class="font-size-15 font-weight-bold mb-3"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="200">UNDER FAVORABLE SMARTWATCHES
                                        </h6>
                                        <div class="mb-4"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="300">
                                            <span class="font-size-13">FROM</span>
                                            <div class="font-size-50 font-weight-bold text-lh-45">
                                                <sup class="">$</sup>749<sup class="">99</sup>
                                            </div>
                                        </div>
                                        <a href="../shop/single-product-fullwidth.html" class="btn btn-primary transition-3d-hover rounded-lg font-weight-normal py-2 px-md-7 px-3 font-size-16"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="400">
                                            Start Buying
                                        </a>
                                    </div>
                                    <div class="col-xl-5 col-6  d-flex align-items-center"
                                        data-scs-animation-in="fadeInUp"
                                        data-scs-animation-delay="500">
                                        <img class="img-fluid" src="https://stackcodes.net/html/madras_themes/electro/assets/img/416X420/img2.png" alt="Image Description">
                                    </div>
                                </div>
                            </div>
                            <div class="js-slide bg-img-hero-center" data-animation-delay="0">
                                <div class="row min-height-420 py-7 py-md-0">
                                    <div class="offset-xl-3 col-xl-4 col-6 mt-md-8">
                                        <h1 class="font-size-64 text-lh-57 font-weight-light"
                                            data-scs-animation-in="fadeInUp">
                                            THE NEW <span class="d-block font-size-55">STANDARD</span>
                                        </h1>
                                        <h6 class="font-size-15 font-weight-bold mb-3"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="200">UNDER FAVORABLE SMARTWATCHES
                                        </h6>
                                        <div class="mb-4"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="300">
                                            <span class="font-size-13">FROM</span>
                                            <div class="font-size-50 font-weight-bold text-lh-45">
                                                <sup class="">$</sup>749<sup class="">99</sup>
                                            </div>
                                        </div>
                                        <a href="../shop/single-product-fullwidth.html" class="btn btn-primary transition-3d-hover rounded-lg font-weight-normal py-2 px-md-7 px-3 font-size-15"
                                            data-scs-animation-in="fadeInUp"
                                            data-scs-animation-delay="400">
                                            Start Buying
                                        </a>
                                    </div>
                                    <div class="col-xl-5 col-6  d-flex align-items-center"
                                        data-scs-animation-in="fadeInRight"
                                        data-scs-animation-delay="500">
                                        <img class="img-fluid" src="../../assets/img/416X420/img3.png" alt="Image Description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Slider Section -->
            <!-- End Products-4-1-4 -->
            <div class="container">
                <!-- Recently Viewed -->
                <RecentlyViewed />
                <!-- Top Categories -->
                <div  v-if="allcats" class="mb-6 bg-white py-6">
                    <div class="container">
                        <div class=" d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0 mb-5">
                            <h3 @click="dejj" class="section-title mb-0 pb-2 font-size-22">{{store.state.user.language.top_categories_this_week}}</h3>
                        </div>
                        <div class="row flex-nowrap flex-md-wrap overflow-auto overflow-md-visble">
                            <div v-for="cat in allcats" :key="cat.id" class="col-md-4 col-lg-3 col-xl-4 col-xl-2gdot4 mb-3 flex-shrink-0 flex-md-shrink-1 shadow-sm">
                                <div class="bg-white overflow-hidden shadow-on-hover h-100 d-flex align-items-center">
                                    <router-link :to="getLink(cat)" href="../shop/product-categories-7-column-full-width.html" class="d-block pr-2 pr-wd-6">
                                        <div class="media align-items-center">
                                            <div class="pt-2">
                                                <img class="img-fluid transform-rotate-15" :src="'http://127.0.0.1:8000/api/images/categories/'+cat.image" alt="Image Description">
                                            </div>
                                            <div class="ml-3 media-body">
                                                <h6 class="mb-0 text-gray-90">{{cat.name}}</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Brand Carousel -->
                <BrandCarousel />
                <!-- Merchant Carousel -->
                <MerchantCarousel />
            </div>
        </main>
</template>

<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
import RecentlyViewed from '../components/RecentlyViewed.vue'
import BrandCarousel from '../components/BrandCarousel.vue'
import MerchantCarousel from '../components/MerchantCarousel.vue'
const allcats = ref(false)
onMounted(()=>{
    document.title = 'Electro'
    store.dispatch('loadallcategories').then(()=>{
        allcats.value = store.state.allcategories
        setCategories()
    })
})
watch(()=>store.state.user.language,()=>{
    if(allcats.value){
        setCategories()
    }
})
const setCategories = () => {
    if (localStorage.getItem('lang') === 'az'){
        allcats.value.forEach(item => {
            item.name = item.translations[0].name
        });
    }else if (localStorage.getItem('lang') === 'en'){
        allcats.value.forEach(item => {
            item.name = item.translations[1].name
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        allcats.value.forEach(item => {
            item.name = item.translations[2].name
        });
    }
}
const getLink = (cat) => {
    if(cat.type === 1){
        return{
            name:'Category',
            params:{
                slug:cat.slug
            }
        }
    }else if(cat.type === 2){
        return{
            name:'SubCategory',
            params:{
                parent:cat.parent.slug,
                slug:cat.slug
            }
        }
    }else if(cat.type === 3){
        return{
            name:'SubSubCategory',
            params:{
                fparent:cat.parent2.slug,
                sparent:cat.parent.slug,
                slug:cat.slug
            }
        }
    }
}
</script>
<style scoped>

</style>
