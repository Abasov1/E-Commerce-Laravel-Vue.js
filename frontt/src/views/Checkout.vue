<template>
<main id="content" role="main" class="checkout-page">
            <div class="container">
                <div v-if="store.state.loading" class="mb-5">
                    <h1 class="text-center">{{store.state.user.language.loading}}</h1>
                </div>
                <div v-if="!store.state.loading && !store.state.user.isLoggedIn" class="mb-5">
                    <h1 class="text-center">{{store.state.user.language.messages.notloggedin}}</h1>
                </div>
                <div v-if="!store.state.loading && store.state.user.isLoggedIn && cart.length === 0" class="mb-5">
                    <h1 class="text-center">No item in cart</h1>
                </div>
                <div v-if="!store.state.loading && store.state.user.isLoggedIn && cart.length != 0" class="mb-5">
                    <h1 class="text-center">{{store.state.user.language.checkout.title}}</h1>
                </div>
                <form @submit.prevent="payMf" v-if="!store.state.loading && store.state.user.isLoggedIn && cart.length != 0" class="js-validate" novalidate="novalidate">
                    <div class="row">
                        <div class="col-lg-5 order-lg-2 mb-7 mb-lg-0">
                            <div class="pl-lg-3 ">
                                <div class="bg-gray-1 rounded-lg">
                                    <!-- Order Summary -->
                                    <div class="p-4 mb-4 checkout-table">
                                        <!-- Title -->
                                        <div class="border-bottom border-color-1 mb-5">
                                            <h3 class="section-title mb-0 pb-2 font-size-25">{{store.state.user.language.checkout.your}}</h3>
                                        </div>
                                        <!-- End Title -->

                                        <!-- Product Content -->
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="product-name">{{store.state.user.language.carto.product}}</th>
                                                    <th class="product-total">{{store.state.user.language.carto.total}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="cr in cart" class="cart_item">
                                                    <td>{{cr.name}} <strong class="product-quantity">× {{cr.pivot.quantity}}</strong></td>
                                                    <td v-text="getPrice(cr.pivot.quantity * cr.price)"></td>
                                                </tr>
                                                <tr class="cart_item">
                                                    <td></td>
                                                    <td v-text="getTotalPrice()"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- End Product Content -->
                                        <button :disabled="proccessing" type="submit" class="btn btn-primary-dark-w btn-block btn-pill font-size-20 mb-3 py-3">{{store.state.user.language.checkout.place}}</button>
                                    </div>
                                    <!-- End Order Summary -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 order-lg-1">
                            <div class="pb-7 mb-7">
                                <!-- Title -->
                                <div class="border-bottom border-color-1 mb-5">
                                    <h3 class="section-title mb-0 pb-2 font-size-25">{{store.state.user.language.checkout.details}}</h3>
                                </div>
                                <!-- End Title -->

                                <!-- Billing Form -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Input -->
                                        <div class="js-form-message mb-6">
                                            <label class="form-label">
                                                {{store.state.user.language.checkout.address}}
                                                <span v-if="addressV" class="text-danger">*</span>
                                            </label>
                                            <input v-model="customer.address" :disabled="proccessing" type="text" class="form-control" name="streetAddress" placeholder="470 Lucy Forks" aria-label="470 Lucy Forks" required="" data-msg="Please enter a valid address." data-error-class="u-has-error" data-success-class="u-has-success">
                                            <span v-if="addressV" style="color:red">{{addressV}}</span>
                                        </div>
                                        <!-- End Input -->
                                    </div>

                                    <div class="col-md-12">
                                        <!-- Input -->
                                        <div class="js-form-message mb-6">
                                            <label class="form-label">
                                                {{store.state.user.language.checkout.city}}
                                                <span v-if="cityV" class="text-danger">*</span>
                                            </label>
                                            <input v-model="customer.city" :disabled="proccessing" type="text" class="form-control" name="cityAddress" placeholder="London" aria-label="London" required="" data-msg="Please enter a valid address." data-error-class="u-has-error" data-success-class="u-has-success" autocomplete="off">
                                            <span v-if="cityV" style="color:red">{{cityV}}</span>
                                        </div>
                                        <!-- End Input -->
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Input -->
                                        <div class="js-form-message mb-6">
                                            <label class="form-label">
                                                {{store.state.user.language.checkout.state}}
                                                <span v-if="stateV" class="text-danger">*</span>
                                            </label>
                                            <input v-model="customer.state" :disabled="proccessing" type="text" class="form-control" name="cityAddress" placeholder="London" aria-label="London" required="" data-msg="Please enter a valid address." data-error-class="u-has-error" data-success-class="u-has-success" autocomplete="off">
                                            <span v-if="stateV" style="color:red">{{stateV}}</span>
                                        </div>
                                        <!-- End Input -->
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Input -->
                                        <div class="js-form-message mb-6">
                                            <label class="form-label">
                                                {{store.state.user.language.checkout.zip}}
                                                <span v-if="zipCodeV" class="text-danger">*</span>
                                            </label>
                                            <input v-model="customer.zip_code" :disabled="proccessing" type="number" class="form-control" name="postcode">
                                            <span v-if="zipCodeV" style="color:red">{{zipCodeV}}</span>
                                        </div>
                                        <!-- End Input -->
                                    </div>
                                    <div class="col-md-12">
                                        <!-- Input -->
                                        <div class="js-form-message mb-6">
                                            <label class="form-label">
                                                {{store.state.user.language.checkout.c_info}}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div id="stripe-card" ref="uqabuqa"></div>
                                            <span style="color:red" v-if="cartV">{{cartV}}</span>
                                        </div>
                                        <!-- End Input -->
                                    </div>
                                </div>
                                <!-- End Billing Form -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
</template>
<script setup>
import {ref,reactive,onMounted,watch} from 'vue'
import store from '../store'
import router from '../router'
import axios from 'axios'
import {loadStripe} from '@stripe/stripe-js'
const stripe = ref(false)
const cardElement = ref(false)
const proccessing = ref(false)
const addressV = ref(false)
const cityV = ref(false)
const stateV = ref(false)
const zipCodeV = ref(false)
const cart = ref([])
const cartV = ref(false)
const uqabuqa = ref(null)
const postalCode = ref(false)
const complete = ref(false)
const customer = reactive({
    name:'',
    email:'',
    address:'',
    city:'',
    state:'',
    zip_code:''
})
onMounted(() => {
    document.title = store.state.user.language.checkout.title
    if(store.state.user.isLoggedIn && store.state.user.data.cart.length != 0){
        cart.value = store.state.user.data.cart
        customer.name = store.state.user.data.name
        customer.email = store.state.user.data.email
        if(store.state.user.data.address){
            customer.address = store.state.user.data.address
        }
        if(store.state.user.data.city){
            customer.city = store.state.user.data.city
        }
        if(store.state.user.data.state){
            customer.state = store.state.user.data.state
        }
        if(store.state.user.data.zip_code){
            customer.zip_code = store.state.user.data.zip_code
        }
        mountCard()
        setPrName()
    }
})
watch(()=>store.state.user.isLoggedIn,()=>{
    if(store.state.user.isLoggedIn){
        if(store.state.user.data.cart != 0){
        mountCard()
        }
        cart.value = store.state.user.data.cart
        customer.name = store.state.user.data.name
        customer.email = store.state.user.data.email
        if(store.state.user.data.address){
            customer.address = store.state.user.data.address
        }
        if(store.state.user.data.city){
            customer.city = store.state.user.data.city
        }
        if(store.state.user.data.state){
            customer.state = store.state.user.data.state
        }
        if(store.state.user.data.zip_code){
            customer.zip_code = store.state.user.data.zip_code
        }
        setPrName()
    }
})
watch(()=>store.state.user.language,()=>{
    if(cart.value){
        setPrName()
    }
    if(addressV){
        validateAddress()
    }
    if(cityV){
        validateCity()
    }
    if(stateV){
        validateState()
    }
    if(zipCodeV){
        validateZip()
    }
    if(cartV){
        validateCart()
    }
})
const mountCard = async() => {
    stripe.value = await loadStripe('pk_test_51MwLYBFv3VNBrMppf8EjniTVfkESSxuKxx8PCq1yNUx1t9bUAIDhDXbyohqLfpSPND8X2FQRxTyxNLwtDKFhLMSO00xudwkKvm')
    const elements = stripe.value.elements()
    cardElement.value = elements.create('card',{
        classes:{
            base:'form-control'
        }
    })
    cardElement.value.mount('#stripe-card')
    cardElement.value.on('change', (event) => {
          if(event.value.postalCode){
            customer.zip_code = event.value.postalCode
            postalCode.value = event.value.postalCode
            validateZip()
          }
          complete.value = event.complete
          validateCart()
    })
}
const payMf = async() => {
    validateAddress()
    validateCity()
    validateState()
    validateZip()
    validateCart()
    if(validateAddress() && validateCity() && validateState() && validateZip() && validateCart()){
        proccessing.value = true
        const {paymentMethod,error} = await stripe.value.createPaymentMethod('card',cardElement.value,{
            billing_details:{
                name: customer.name,
                email:customer.email,
                address:{
                    line1:customer.address,
                    city:customer.city,
                    state:customer.state,
                    postal_code:customer.zip_code
                }
            }
        })
        if(error){
            alert(error)
        }else{
            customer.payment_method_id = paymentMethod.id
            customer.amount = cart.value.reduce((acc,item)=> acc + (item.pivot.quantity * item.price),0)
            customer.cart = JSON.stringify(cart.value)
            axios.post('http://127.0.0.1:8000/api/purchase',customer,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                proccessing.value = false
                store.commit('clearCart')
                router.push({name:'Sold'})
            }).catch((error)=>{
                proccessing.value = false
                alert('Something bad is happened')
            })
        }
    }
}
const setPrName = () =>{
        if (localStorage.getItem('lang') === 'az'){
            cart.value.forEach(item => {
                item.name = item.translations[0].name
            });
        }else if (localStorage.getItem('lang') === 'en'){
            cart.value.forEach(item => {
                item.name = item.translations[1].name
            });
        }else if (localStorage.getItem('lang') === 'ru'){
            cart.value.forEach(item => {
                item.name = item.translations[2].name
            });
        }
}
watch(()=>customer.address,()=>{
    validateAddress()
})
watch(()=>customer.city,()=>{
    validateCity()
})
watch(()=>customer.state,()=>{
    validateState()
})
watch(()=>customer.zip_code,()=>{
    validateZip()
})
watch(()=>complete.value,()=>{
    validateCart()
})
const validateAddress = () => {
    if(customer.address === ''){
        addressV.value = store.state.user.language.checkout.validations.a_required
        return false
    }else{
        addressV.value = false
        return true
    }
}
const validateCity = () => {
    if(customer.city === ''){
        cityV.value = store.state.user.language.checkout.validations.c_required
        return false
    }else{
        cityV.value = false
        return true
    }
}
const validateState = () => {
    if(customer.state === ''){
        stateV.value = store.state.user.language.checkout.validations.s_required
        return false
    }else{
        stateV.value = false
        return true
    }
}
const validateZip = () => {
    if(customer.zip_code === ''){
        zipCodeV.value = store.state.user.language.checkout.validations.z_required
        return false
    }else if(customer.zip_code > 99999){
        customer.zip_code = 99999
        return false
    }else if(postalCode.value && postalCode.value != customer.zip_code){
        zipCodeV.value = store.state.user.language.checkout.validations.z_match
        return false
    }else{
        zipCodeV.value = false
        return true
    }
}
const validateCart = () => {
    if(!complete.value){
        cartV.value = store.state.user.language.checkout.validations.card_required
        return false
    }else{
        cartV.value = false
        return true
    }
}
const getPrice = (pr) => {
    return (pr / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
const getTotalPrice = () => {
    return ((cart.value.reduce((acc,item)=>acc + (item.pivot.quantity * item.price),0)) / 100).toLocaleString('en-US',{style:'currency',currency:'USD'});
}
</script>
