<template>
    <main id="content" role="main">
            <!-- Slider Section -->
            <div class="mb-5">
                    <Slider />
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
import Slider from '../components/Slider.vue'
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
