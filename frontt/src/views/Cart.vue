<template>
            <main id="content" role="main" class="cart-page">
                <div class="mb-4">
                    <h1 @click="niyee" v-if="store.state.loading" class="text-center">{{store.state.user.language.loading}}</h1>
                    <h1 v-if="!store.state.loading && !store.state.user.isLoggedIn" class="text-center">{{store.state.user.language.messages.notloggedin}}</h1>
                    <h1 v-if="!store.state.loading && store.state.user.isLoggedIn && products.length === 0" class="text-center">{{store.state.user.language.carto.no_item}}</h1>
                    <h1 v-if="!store.state.loading && store.state.user.isLoggedIn && products.length != 0" class="text-center">{{store.state.user.language.carto.title}}</h1>
                </div>
                <div v-if="store.state.user.isLoggedIn && products.length != 0" class="mb-10 cart-table">
                    <form class="mb-4" action="#" method="post">
                        <div :class="[ploading ? 'ploading' : '']"></div>
                        <table class="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">{{store.state.user.language.carto.product}}</th>
                                    <th class="product-price">{{store.state.user.language.carto.price}}</th>
                                    <th class="product-quantity w-lg-15">{{store.state.user.language.carto.quantity}}</th>
                                    <th class="product-subtotal">{{store.state.user.language.carto.total}}</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-if="products" v-for="cr in products" :key="cr.id" class="">
                                    <td class="text-center">
                                        <a @click.prevent="removeWish(cr.id)" href="#" class="text-gray-32 font-size-26">×</a>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <router-link :to="{name:'Product',params:{slug:cr.slug}}"><img class="img-fluid max-width-100 p-1 border border-color-1" :src="'http://127.0.0.1:8000/api/images/products/'+cr.images[0].image" alt="Image Description"></router-link>
                                    </td>

                                    <td data-title="Product">
                                        <router-link :to="{name:'Product',params:{slug:cr.slug}}" class="text-gray-90"><span :style="[cr.quantity === 0 ? 'text-decoration:line-through;text-decoration-color:red;' : '']">{{cr.name}}</span><span v-if="cr.quantity === 0" style="color:red;margin-left:20px;">{{store.state.user.language.availability.replace('{x}',0)}}</span></router-link>
                                    </td>

                                    <td data-title="Price">
                                        <span class="" v-text="getPrice(cr.price)"></span>
                                    </td>

                                    <td data-title="Quantity">
                                        <span class="sr-only">{{store.state.user.language.carto.quantity}}</span>
                                        <!-- Quantity -->
                                        <div class="border rounded-pill py-1 width-122 w-xl-80 px-3 border-color-1">
                                            <div class="js-quantity row align-items-center">
                                                <div class="col">
                                                    <input readonly class="js-result form-control h-auto border-0 rounded p-0 shadow-none" type="text" :value="cr.pivot.quantity">
                                                </div>
                                                <div class="col-auto pr-1">
                                                    <a v-if="cr.pivot.quantity != 1 && cr.pivot.quantity != 0" @click.prevent="changeQuantity(cr.id,cr.quantity,cr.pivot.quantity,2)" class="js-minus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="bi bi-dash btn-icon__inner"></small>
                                                    </a>
                                                    <a v-if="cr.pivot.quantity != cr.quantity && cr.pivot.quantity <= cr.quantity" @click.prevent="changeQuantity(cr.id,cr.quantity,cr.pivot.quantity,1)" class="js-plus btn btn-icon btn-xs btn-outline-secondary rounded-circle border-0" href="javascript:;">
                                                        <small class="bi bi-plus btn-icon__inner"></small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Quantity -->
                                    </td>

                                    <td data-title="Total">
                                        <span class="" v-text="getPrice(cr.pivot.quantity * cr.price)"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="border-top space-top-2 justify-content-center">
                                        <div class="pt-md-3">
                                            <div class="d-block d-md-flex flex-center-between">
                                                <div class="mb-3 mb-md-0 w-xl-40">

                                                </div>
                                                <div class="d-md-flex">
                                                    <router-link :to="{name:'Checkout'}" id="battan" class="btn btn-primary-dark-w ml-md-2 px-5 px-md-4 px-lg-5 w-100 w-md-auto d-none d-md-inline-block">{{store.state.user.language.carto.proceed}}</router-link>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </main>
</template>
<script setup>
import store from '../store'
import {ref,reactive,computed,onMounted,watch} from 'vue'
const ploading = ref(false)
const products = ref(false)
const formData = reactive({
    id:null,
    quantity:null,
    increase:false,
    decrase:false,
    fix:false
})
onMounted(()=>{
    document.title = store.state.user.language.carto.title
    if(store.state.user.isLoggedIn){
        products.value = store.state.user.data.cart
        products.value.forEach(item => {
            if(item.quantity < item.pivot.quantity){
                changeQuantity(item.id,item.quantity,item.quantity,3)
            }
            if(item.quantity === 0){
                removeWish(item.id)
            }
        });
        setPrName()
    }
})
watch(()=>store.state.user.data,()=>{
    if(store.state.user.isLoggedIn){
        products.value = store.state.user.data.cart
        products.value.forEach(item => {
            if(item.quantity < item.pivot.quantity){
                changeQuantity(item.id,item.quantity,item.quantity,3)
            }
            if(item.quantity === 0){
                removeWish(item.id)
            }
        });
        setPrName()
    }
})
watch(()=>store.state.user.language,()=>{
    document.title = store.state.user.language.carto.title
        setPrName()
})
const setPrName = () =>{
    if(products.value){
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
}
const changeQuantity = (id,cquantity,quantity,n) => {
    ploading.value = true
    formData.id = id
    formData.quantity = quantity
    if(n === 1){
        if(cquantity === quantity){
            return
        }
        formData.increase = true
        formData.decrase = false
        formData.fix = false
    }else if(n === 2){
        if(quantity === 1){
            return
        }
        formData.increase = false
        formData.decrase = true
        formData.fix = false
    }else if(n === 3){
        formData.increase = false
        formData.decrase = false
        formData.fix = true
    }
    store.dispatch('changeQuantity',formData).then(()=>{
        ploading.value = false
    }).catch((error)=>{
        ploading.value = false
    })
}
const removeWish = (id) => {
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
input[readonly] {
    background-color: inherit;
}
</style>
