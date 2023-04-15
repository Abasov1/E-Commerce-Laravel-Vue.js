<template>
    <div class="col-6 col-md-3 col-wd-2gdot4" style="margin: 5px 0px;padding: 0px 5px;">
        <div :class="[pLoading === pr.id ? 'ploading' : '']"></div>
                <div class="h-100 shadow" style="background-color: white;">
                    <div class="p-md-3 row no-gutters">
                            <div>
                                <div class="mb-2"><a @click.prevent class="font-size-12 text-gray-5">{{pr.category.name}}</a></div>
                                <h6 ><router-link :to="{name:'Product',params:{slug:pr.slug}}" class="text-blue font-weight-bold">{{pr.name}}</router-link></h6>
                            </div>
                            <div class="col col-lg-auto product-media-left">
                                <router-link :to="{name:'Product',params:{slug:pr.slug}}" class="max-width-150 d-block"><img class="img-fluid" :src="'http://127.0.0.1:8000/api/images/products/'+pr.images[0].image" alt="Image Description"></router-link>
                            </div>
                            <div class="col  pl-2 pl-lg-3 mr-xl-2 mr-wd-1">

                            <div class="flex-center-between mb-3">
                                <div class="prodcut-price">
                                    <div class="text-gray-100" v-text="getPrice(pr.price)"></div>
                                </div>
                                <div class="d-none d-xl-block prodcut-add-cart">
                                  <a v-if="!store.state.user.isLoggedIn" id="searchClassicInvoker" class="btn-add-cart btn-primary transition-3d-hover" href="javascript:;" role="button"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Search"
                                    aria-controls="searchClassic"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-unfold-target="#searchClassic"
                                    data-unfold-type="css-animation"
                                    data-unfold-duration="300"
                                    data-unfold-delay="300"
                                    data-unfold-hide-on-scroll="true"
                                    data-unfold-animation-in="slideInUp"
                                    data-unfold-animation-out="fadeOut"><i class="bi bi-cart"></i></a>
                                    <a v-if="store.state.user.isLoggedIn" @click.prevent="addCart(pr.id)" :style="[store.state.user.data.cart.some(item => item.id === pr.id) ? 'background-color:green;' : '']" href="../shop/single-product-fullwidth.html" class="btn-add-cart btn-primary transition-3d-hover"><i v-if="store.state.user.data.cart.some(item => item.id === pr.id)" class="bi bi-cart-check"></i><i v-else class="bi bi-cart"></i></a>
                                </div>
                            </div>
                            <div v-if="!store.state.loading">
                                <div class="border-top pt-2 flex-center-between flex-wrap">
                                    <a v-if="!store.state.user.isLoggedIn" @click.prevent="dejj" class="text-gray-6 font-size-13 transition-3d-hover" style="cursor:pointer"><i class="bi bi-heart mr-1 font-size-15"></i>{{store.state.user.language.wishlist}}</a>
                                    <a v-if="!store.state.user.isLoggedIn" @click.prevent="dejj" class="text-gray-6 font-size-13 transition-3d-hover" style="cursor:pointer"><i class="bi bi-cart mr-1 font-size-15"></i>{{store.state.user.language.cart}}</a>
                                    <a v-if="store.state.user.isLoggedIn" @click.prevent="addWish(pr.id)" href="../shop/wishlist.html" class="text-gray-6 font-size-13 transition-3d-hover" ><i v-if="store.state.user.data.wishlist.some(item => item.id === pr.id)" class="bi bi-heart-fill mr-1 font-size-15" style="color:crimson;"></i><i v-else class="bi bi-heart mr-1 font-size-15"></i>{{store.state.user.language.wishlist}}</a>
                                    <a v-if="store.state.user.isLoggedIn" @click.prevent="addCart(pr.id)" href="../shop/wishlist.html" class="text-gray-6 font-size-13 transition-3d-hover"><i v-if="store.state.user.data.cart.some(item => item.id === pr.id)" class="bi bi-cart-check-fill mr-1 font-size-15" style="color:green;"></i><i v-else class="bi bi-cart mr-1 font-size-15"></i>{{store.state.user.language.cart}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</template>
<script setup>
import store from '../store'
import {ref,onMounted,watch} from 'vue'
const pLoading = ref([])
const props = defineProps({
    pr: Object
})
const pr = ref(props.pr)
onMounted(()=>{
    setCat()
})
watch(()=>store.state.user.language,()=>{
    setCat()
})
const setCat = () => {
    if (localStorage.getItem('lang') === 'az'){
        pr.value.category.name = pr.value.category.translations[0].name
    }else if (localStorage.getItem('lang') === 'en'){
        pr.value.category.name = pr.value.category.translations[1].name
    }else if (localStorage.getItem('lang') === 'ru'){
        pr.value.category.name = pr.value.category.translations[2].name
    }
}
const addWish = (id) => {
    pLoading.value = id
    store.dispatch('addWish',id).then(()=>{
        pLoading.value = false
    }).catch((error)=>{
        pLoading.value = false
    })
}
const addCart = (id) => {
    pLoading.value = id
    store.dispatch('addCart',id).then(()=>{
        pLoading.value = false
    }).catch((error)=>{
        pLoading.value = false
    })
}
const dejj = () => {
    setTimeout(function() {
        $('#sidebarNavToggler').click();
    }, 100);
}
const getPrice = (pr) => {
    return (pr / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
</script>
<style scoped>
.ploading{
    position:absolute;
    width:100%;
    height:100%;
    background-color:white;
    opacity:50%;
    z-index:999999;
}
</style>
