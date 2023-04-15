<template>
    <main id="content" role="main" class="cart-page">
                <div class="mb-4">
                    <h1 v-if="store.state.loading" class="text-center">{{store.state.user.language.loading}}</h1>
                    <h1 v-if="!store.state.loading && !store.state.user.isLoggedIn" class="text-center">{{store.state.user.language.messages.notloggedin}}</h1>
                    <h1 v-if="!store.state.loading && store.state.user.isLoggedIn && orders.length === 0" class="text-center">{{store.state.user.language.checkout.n_purchased}}</h1>
                    <h1 v-if="!store.state.loading && store.state.user.isLoggedIn && orders.length != 0" class="text-center">{{store.state.user.language.checkout.purchased}}</h1>
                </div>
                <div v-if="store.state.user.isLoggedIn && orders.length != 0" class="mb-10 cart-table">
                        <table v-if="orders" v-for="or in orders" :key="or.id" class="table" cellspacing="0">
                            <thead>
                                <h5 style="margin-top:50px;">{{or.date}}</h5>
                                <tr>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">{{store.state.user.language.carto.product}}</th>
                                    <th class="product-price">{{store.state.user.language.carto.price}}</th>
                                    <th class="product-quantity w-lg-15">{{store.state.user.language.carto.quantity}}</th>
                                    <th class="product-subtotal">{{store.state.user.language.carto.total}}</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-for="cr in or.products" :key="cr.id" class="">
                                    <td class="d-none d-md-table-cell">
                                        <router-link :to="{name:'Product',params:{slug:cr.slug}}"><img class="img-fluid max-width-100 p-1 border border-color-1" :src="'http://127.0.0.1:8000/api/images/products/'+cr.images[0].image" alt="Image Description"></router-link>
                                    </td>

                                    <td data-title="Product">
                                        <router-link :to="{name:'Product',params:{slug:cr.slug}}" class="text-gray-90">{{cr.name}}</router-link>
                                    </td>

                                    <td data-title="Price">
                                        <span class="" v-text="getPrice(cr.price)"></span>
                                    </td>

                                    <td data-title="Quantity">
                                        <span class="">{{cr.pivot.quantity}}</span>
                                    </td>

                                    <td data-title="Total">
                                        <span class="" v-text="getPrice(cr.pivot.quantity * cr.price)"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td data-title="Quantity">
                                        <span><b>{{store.state.user.language.carto.total}}:</b></span>
                                    </td>
                                    <td data-title="Total">
                                        <span class="" v-text="getTotalPrice(or.products)"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav class="d-md-flex justify-content-between align-items-center border-top pt-3" aria-label="Page navigation example">
                            <div class="text-center text-md-left mb-3 mb-md-0"></div>
                            <ul class="pagination mb-0 pagination-shop justify-content-center justify-content-md-start">
                                <li @click.prevent="changePage(1)" class="page-item"><a class="page-link" :class="formData.page === 1 ? 'current' : ''" href="#">1</a></li>
                                <li v-if="formData.page > 2" class="page-item"><a class="page-link" href="#">...</a></li>
                                <li v-if="lastPage" v-for="n in lastPage" :key="n" class="page-item" @click.prevent="changePage(n)" v-show="n != 1&& n > formData.page-2  && formData.page + 2 > n"><a class="page-link" :class="n === formData.page ? 'current' : ''" href="#">{{n}}</a></li>
                                <li v-if="lastPage - 2 > formData.page" class="page-item"><a class="page-link" href="#">...</a></li>
                                <li v-if="lastPage" class="page-item" @click.prevent="changePage(lastPage)" v-show="formData.page + 2 <= lastPage"><a class="page-link" href="#">{{lastPage}}</a></li>
                            </ul>
                        </nav>
                </div>
            </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
const products = ref(false)
const lastPage = ref(false)
const orders = ref(false)
const formData = reactive({
    page:1
})
watch(()=>store.state.user.isLoggedIn,()=>{
    if(store.state.user.isLoggedIn){
        loadProducts()
    }
})
watch(()=>store.state.user.language,()=>{
    document.title = store.state.user.language.checkout.purchased
})
onMounted(()=>{
    document.title = store.state.user.language.checkout.purchased
    if(!store.state.user.loading && store.state.user.isLoggedIn){
        loadProducts()
    }
})
const loadProducts = () => {
    store.dispatch('soldProducts',formData).then(()=>{
        orders.value = store.state.soldData[0].data
        lastPage.value = store.state.soldData[1]
        window.scroll(0,0)
    })
}
const changePage = (n) => {
    formData.page = n
    loadProducts()
}
const getPrice = (pr) => {
    return (pr / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
const getTotalPrice = (pr) => {
    return ((pr.reduce((acc,item)=>acc + (item.pivot.quantity * item.price),0)) / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
</script>
