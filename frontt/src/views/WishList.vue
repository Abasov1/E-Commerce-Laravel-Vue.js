<template>
        <main id="content" role="main" class="cart-page">
            <div v-if="store.state.loading || !store.state.user.isLoggedIn" class="container">
                <div class="my-6">
                    <h1 v-if="store.state.loading" class="text-center">{{store.state.user.language.loading}}</h1>
                    <h1 v-if="!store.state.loading && !store.state.user.isLoggedIn" class="text-center">{{store.state.user.language.messages.notloggedin}}</h1>
                </div>
            </div>
            <div v-if="store.state.user.isLoggedIn" class="container">
                <div class="my-6">
                    <h1 v-if="products.length === 0" class="text-center">{{store.state.user.language.wish.no_item}}</h1>
                    <h1 v-if="products.length != 0" class="text-center">{{store.state.user.language.wish.title}}</h1>
                </div>
                <div v-if="products.length != 0" class="mb-16 wishlist-table">
                    <form class="mb-4" action="#" method="post">
                        <div class="table-responsive">
                            <div :class="[ploading ? 'ploading' : '']"></div>
                            <table class="table" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name">{{store.state.user.language.wish.product}}</th>
                                        <th class="product-price">{{store.state.user.language.wish.price}}</th>
                                        <th class="product-Stock w-lg-15">&nbsp;</th>
                                        <th class="product-subtotal min-width-200-md-lg">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="products" v-for="wish in products.slice(0,sliceNumber)" :key="wish.id">
                                        <td class="text-center">
                                            <a @click.prevent="removeWish(wish.id)" href="#" class="text-gray-32 font-size-26">×</a>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                        <router-link :to="{name:'Product',params:{slug:wish.slug}}"><img class="img-fluid max-width-100 p-1 border border-color-1" :src="'http://127.0.0.1:8000/api/images/products/'+wish.images[0].image" alt="Image Description"></router-link>
                                        </td>

                                        <td data-title="Product">
                                            <router-link :to="{name:'Product',params:{slug:wish.slug}}" class="text-gray-90">{{wish.name}}</router-link>
                                        </td>

                                        <td data-title="Unit Price">
                                            <span class="" v-text="getPrice(wish.price)"></span>
                                        </td>

                                        <td data-title="Stock Status">

                                        </td>

                                        <td>
                                            <button @click="addCart(wish.id)" type="button" style="min-width:80%;max-width:80%" class="btn btn-soft-secondary mb-3 mb-md-0 font-weight-normal px-5 px-md-4 px-lg-5 w-100 w-md-auto" :style="store.state.user.data.cart.some(item=>item.id === wish.id) ? 'color:white;background-color:rgb(119,131,143)' : 'background-color:rgb(241,242,244);color:black;'">
                                                <span v-if="store.state.user.data.cart.some(item=>item.id === wish.id)">{{store.state.user.language.already_in_cart}}</span>
                                                <span v-else>{{store.state.user.language.add_cart}}</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="width:100%;display:flex;justify-content:space-between;">
                                <span v-if="sliceNumber < store.state.user.data.wishlist.length + 1" @click="sliceNumber = sliceNumber + 4" style="cursor:pointer">{{store.state.user.language.show_more}}</span><br>
                                <span v-if="sliceNumber > 4 && store.state.user.data.wishlist.length > 4" @click="sliceNumber = 4" style="cursor:pointer">{{store.state.user.language.show_less}}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
</template>
<script setup>
import store from '../store'
import {ref,computed,watch,onMounted} from 'vue'
const sliceNumber = ref(4)
const ploading = ref(false)
const products = ref(false)
const removeWish = (id) => {
    ploading.value = true
    store.dispatch('addWish',id).then(()=>{
        ploading.value = false
    })
}
onMounted(()=>{
    document.title = store.state.user.language.wish.title
    if(store.state.user.isLoggedIn){
        products.value = store.state.user.data.wishlist
        setPrName()
    }
})
watch(()=>store.state.user.language,()=>{
    document.title = store.state.user.language.wish.title
    if(products.value){
        setPrName()
    }
})
watch(()=>store.state.user.data,()=>{
    products.value = store.state.user.data.wishlist
    setPrName()
})
const setPrName = () =>{
    if (localStorage.getItem('lang') === 'az'){
        products.value.forEach(item => {
            item.name = item.translations[0].name
        });
    }else if (localStorage.getItem('lang') === 'en'){
        products.value.forEach(item => {
            item.name = item.translations[1].name
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        products.value.forEach(item => {
            item.name = item.translations[2].name
        });
    }
}
const addCart = (id) => {
    ploading.value = true
    store.dispatch('addCart',id).then(()=>{
        ploading.value = false
    }).catch((error)=>{
        ploading.value = false
    })
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
