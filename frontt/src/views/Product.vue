<template>
<main id="content" role="main">
            <div v-if="product"  class="container">
                <!-- Single Product Body -->
                <div class="mb-14">
                    <div class="row">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <div class="d-flex justify-content-center align-items-center" style="min-width:100%;min-height:70%;">
                                <div class="js-slide d-flex justify-content-center align-items-center" style="min-width:100%;min-height:100%;">
                                    <img class="img-fluid d-flex justify-content-center"  style="min-width:300px;max-width:300px;" :src="'http://127.0.0.1:8000/api/images/products/'+product.images[activeImage].image" alt="Image Description">
                                </div>
                            </div>

                            <div style="position:relative; display:flex; justify-content:space-between; align-items:center; margin-right:1rem;">
                                <a @click.prevent="activePage--" v-if="activePage != 0" href="#">
                                    <i class="bi bi-chevron-left" style="font-size: 1rem;"></i>
                                </a>
                                <div v-for="n in product.images.length" :key="n" >
                                    <div class="js-slide" v-if="n - 1 > activePage -1 && n - 1 < activePage + 5" style="cursor: pointer;width:80px;display:inline-block;" :style="[activeImage === n - 1 ? 'border-bottom:solid orange;' : '']">
                                        <img @click="activeImage = n - 1" class="img-fluid" :src="'http://127.0.0.1:8000/api/images/products/'+product.images[n - 1].image" alt="Image Description">
                                    </div>
                                </div>

                                <a v-if="product.images.length > 4 && product.images.length - 5 > activePage" @click.prevent="product.images.length - 5 != activePage ? activePage++ : activePage = activePage" style="cursor:pointer" href="#">
                                    <i class="bi bi-chevron-right" style="font-size: 1.4rem;z-index:900"></i>
                                </a>
                            </div>

                        </div>
                        <div class="col-md-6 col-lg-4 col-xl-4 mb-md-6 mb-lg-0">
                            <div class="mb-2">
                                <a href="#" class="font-size-12 text-gray-5 mb-2 d-inline-block">{{product.category.name}}</a>
                                <h2 class="font-size-25 text-lh-1dot2">{{product.name}}</h2>
                                <div class="mb-2">
                                    <a @click.prevent class="d-inline-flex align-items-center small font-size-15 text-lh-1" href="#">
                                        <div v-if="product.revcount != 0" class="text-warning mr-2">
                                            <small :class="[((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1) < 1 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1) < 2 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1) < 3 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1) < 4 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1) < 5 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                        </div>
                                        <div v-else class="text-warning mr-2">
                                            <small class="bi bi-star text-muted"></small>
                                            <small class="bi bi-star text-muted"></small>
                                            <small class="bi bi-star text-muted"></small>
                                            <small class="bi bi-star text-muted"></small>
                                            <small class="bi bi-star text-muted"></small>
                                        </div>
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <ul class="font-size-14 pl-3 ml-1 text-gray-110">
                                        <li v-for="inf in informations.body.slice(0,4)" :key="inf.id">{{inf.body}}</li>
                                    </ul>
                                </div>
                                <p>
                                    <strong>{{store.state.user.language.search_bar.brand}}: </strong>{{product.brand.name}} <br>
                                    <strong>{{store.state.user.language.search_bar.merchant}}: </strong>{{product.merchant.name}}
                                </p>
                            </div>
                        </div>
                        <div v-if="!store.state.loading" class="mx-md-auto mx-lg-0 col-md-6 col-lg-4 col-xl-3">
                            <div :class="[pLoading ? 'ploading' : '']"></div>
                            <div class="mb-2">
                                <div class="card p-5 border-width-2 border-color-1 borders-radius-17">
                                    <div class="text-gray-9 font-size-14 pb-2 border-color-1 border-bottom mb-3"><span class="text-green font-weight-bold">{{stock}}</span></div>
                                    <div class="mb-3">
                                        <div class="font-size-36" v-text="getPrice(product.price)"></div>
                                    </div>

                                    <div v-if="!store.state.user.isLoggedIn" class="mb-2 pb-0dot5">
                                        <a @click.prevent="dejj" href="#" class="btn btn-block btn-primary-dark"><i class="bi bi-cart mr-2 font-size-15"></i>{{store.state.user.language.add_cart}}</a>
                                    </div>
                                    <div v-if="!store.state.user.isLoggedIn" class="mb-3">
                                        <a @click.prevent="dejj" href="#" class="btn btn-block btn-dark"><i class="bi bi-heart mr-2 font-size-15"></i>{{store.state.user.language.wishlist}}</a>
                                    </div>
                                    <div v-if="store.state.user.isLoggedIn" class="mb-2 pb-0dot5">
                                        <a @click.prevent="addCart(product.id)" href="#" class="btn btn-block btn-primary-dark" :style="[store.state.user.data.cart.some(item => item.id === product.id) ? 'background-color:green' : '']"><span v-if="store.state.user.data.cart.some(item => item.id === product.id)" ><i class="bi bi-cart-check-fill mr-1 font-size-15" style="color:white;"></i>{{store.state.user.language.already_in_cart}}</span><span v-else><i class="bi bi-cart mr-1 font-size-15"></i> {{store.state.user.language.add_cart}}</span></a>
                                    </div>
                                    <div v-if="store.state.user.isLoggedIn" class="mb-3">
                                        <a @click.prevent="addWish(product.id)" href="#" class="btn btn-block btn-dark" :style="[store.state.user.data.wishlist.some(item => item.id === product.id) ? 'background-color:crimson' : '']"><i v-if="store.state.user.data.wishlist.some(item => item.id === product.id)" class="bi bi-heart-fill mr-1 font-size-15" style="color:white;"></i><i v-else class="bi bi-heart mr-1 font-size-15"></i>{{store.state.user.language.wishlist}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Product Body -->
                <div class="mb-8">
                    <div class="position-relative position-md-static px-md-6">
                        <ul class="nav nav-classic nav-tab nav-tab-lg justify-content-xl-center flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble border-0 pb-1 pb-xl-0 mb-n1 mb-xl-0" id="pills-tab-8" role="tablist">
                            <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                <a class="nav-link active" id="Jpills-three-example1-tab" data-toggle="pill" href="#Jpills-three-example1" role="tab" aria-controls="Jpills-three-example1" aria-selected="false">{{store.state.user.language.spesifications}}</a>
                            </li>
                            <li class="nav-item flex-shrink-0 flex-xl-shrink-1 z-index-2">
                                <a class="nav-link" id="Jpills-four-example1-tab" data-toggle="pill" href="#Jpills-four-example1" role="tab" aria-controls="Jpills-four-example1" aria-selected="false">{{store.state.user.language.reviews}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Tab Content -->
                    <div class="borders-radius-17 border p-4 mt-4 mt-md-0 px-lg-10 py-lg-9">
                        <div class="tab-content" id="Jpills-tabContent">
                            <div class="tab-pane fade active show" id="Jpills-three-example1" role="tabpanel" aria-labelledby="Jpills-three-example1-tab">
                                <div class="mx-md-5 pt-1">
                                    <div class="table-responsive" style="overflow:hidden;">
                                        <div class="row" style="overflow:hidden;">
                                            <div class="col-6">
                                                <h6 v-for="tit in informations.title" :key="tit.id">{{tit.title}}</h6>
                                            </div>
                                            <div class="col-6">
                                                <h6 v-for="tit in informations.body" :key="tit.id">{{tit.body}}</h6>
                                            </div>
                                        </div>
                                        <b style="cursor:pointer;" @click="showMore" v-if="!showingMore">{{store.state.user.language.show_all}}</b>
                                        <b style="cursor:pointer;" @click="showLess" v-if="showingMore">{{store.state.user.language.show_less}}</b>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Jpills-four-example1" role="tabpanel" aria-labelledby="Jpills-four-example1-tab">
                                <div class="row mb-8">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h3 class="font-size-18 mb-6">{{product.revcount}} {{store.state.user.language.review.review}}</h3>
                                            <h2 v-if="product.revcount != 0" class="font-size-30 font-weight-bold text-lh-1 mb-0">{{((product.fivestar * 5 + product.fourstar * 4 + product.threestar * 3 + product.twostar * 2 + product.joestar) / product.revcount).toFixed(1)}}</h2>
                                            <h2 v-else class="font-size-30 font-weight-bold text-lh-1 mb-0">0</h2>
                                            <div class="text-lh-1">{{store.state.user.language.review.overall}}</div>
                                        </div>

                                        <!-- Ratings -->
                                        <ul class="list-unstyled">
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1" href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar" :style="'width:'+product.fivepercent+'%;'" :aria-valuenow="product.fivepercent" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{product.fivestar}}</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1" href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar" :style="'width:'+product.fourpercent+'%;'" :aria-valuenow="product.fourpercent" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{product.fourstar}}</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1" href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar" :style="'width:'+product.threepercent+'%;'" :aria-valuenow="product.threepercent" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{product.threestar}}</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1" href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar" :style="'width:'+product.twopercent+'%;'" :aria-valuenow="product.twopercent" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{product.twostar}}</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-1">
                                                <a class="row align-items-center mx-gutters-2 font-size-1" href="javascript:;">
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="text-warning text-ls-n2 font-size-16" style="width: 80px;">
                                                            <small class="bi bi-star-fill"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                            <small class="bi bi-star-fill text-muted"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto mb-2 mb-md-0">
                                                        <div class="progress ml-xl-5" style="height: 10px; width: 200px;">
                                                            <div class="progress-bar" role="progressbar" :style="'width:'+product.joepercent+'%;'" :aria-valuenow="product.joepercent" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto text-right">
                                                        <span class="text-gray-90">{{product.joestar}}</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                        <!-- End Ratings -->
                                    </div>
                                    <div v-if="!reviewLoading" class="col-md-6">
                                        <div :class="[revProccess ? 'ploading' : '']"></div>
                                        <h3 class="font-size-18 mb-5"><span v-if="formData.updating">{{store.state.user.language.review.update}}</span><span v-else>{{store.state.user.language.review.add}}</span></h3>
                                        <!-- Form -->
                                        <form @submit.prevent="addReview" class="js-validate">
                                            <div class="row align-items-center mb-4">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="rating" class="form-label mb-0">{{store.state.user.language.review.your}}</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <a @click.prevent href="#" class="d-block">
                                                        <div class="text-warning text-ls-n2 font-size-16">
                                                            <small @click="formData.star = 1" :class="[formData.star < 1 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                                            <small @click="formData.star = 2" :class="[formData.star < 2 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                                            <small @click="formData.star = 3" :class="[formData.star < 3 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                                            <small @click="formData.star = 4" :class="[formData.star < 4 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                                            <small @click="formData.star = 5" :class="[formData.star < 5 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                                            <span v-if="starR" style="color:red"> - {{store.state.user.language.required}}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="js-form-message form-group mb-3 row">
                                                <div class="col-md-4 col-lg-3">
                                                    <label for="descriptionTextarea" class="form-label">{{store.state.user.language.review.your}}</label>
                                                </div>
                                                <div class="col-md-8 col-lg-9">
                                                    <textarea v-model="formData.body" class="form-control" rows="3" id="descriptionTextarea"
                                                    data-msg="Please enter your message."
                                                    data-error-class="u-has-error"
                                                    data-success-class="u-has-success"></textarea>
                                                    <div v-if="bodyR" style="color:red">{{store.state.user.language.required}}</div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="offset-md-4 offset-lg-3 col-auto">
                                                    <button type="submit" class="btn btn-primary-dark btn-wide"><span v-if="formData.updating">{{store.state.user.language.review.update}}</span><span v-else>{{store.state.user.language.review.add}}</span></button>
                                                    <button v-if="formData.updating" @click="deleteReview" type="button" class="btn btn-primary-dark btn-wide">{{store.state.user.language.review.delete}}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Form -->
                                    </div>
                                </div>
                                <!-- Review -->
                                <div v-if="product" v-for="rew in product.reviews" :key="rew.id" class="border-bottom border-color-1 pb-4 mb-4">
                                    <!-- Review Rating -->
                                    <div class="d-flex justify-content-between align-items-center text-secondary font-size-1 mb-2">
                                        <div class="text-warning text-ls-n2 font-size-16">
                                            <small :class="[rew.star < 1 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[rew.star < 2 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[rew.star < 3 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[rew.star < 4 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <small :class="[rew.star < 5 ? 'bi bi-star text-muted' : 'bi bi-star-fill']"></small>
                                            <span v-if="rew.isBought" style="color:green;font-size:11px;"> - Bought this product</span>
                                        </div>
                                    </div>
                                    <!-- End Review Rating -->

                                    <p class="text-gray-90">{{rew.body}}</p>

                                    <!-- Reviewer -->
                                    <div class="mb-2">
                                        <strong>{{rew.user.name}}</strong>
                                        <span class="font-size-13 text-gray-23">- {{rew.date}}</span>
                                    </div>
                                    <!-- End Reviewer -->
                                </div>
                                <!-- End Review -->
                            </div>
                        </div>
                    </div>
                    <!-- End Tab Content -->
                </div>
            </div>
        </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
const product = ref(false)
const starR = ref(false)
const bodyR = ref(false)
const review = ref(false)
const reviewLoading = ref(true)
const revProccess = ref(false)
const activeImage = ref(0)
const activePage = ref(0)
const informations = reactive({
    title:null,
    body:null
})
const showingMore = ref(false)
const pLoading = ref(false)
const stock = ref(false)
const props = defineProps({
    slug: String
})
const formData = reactive({
    star:0,
    body:'',
    product_id:null,
    updating:null,
    deleting:null
})
const kartof = reactive({
    id:null
})
watch(()=>formData.body,()=>{
    if(formData.body === ''){
        bodyR.value = true
    }else{
        bodyR.value = false
    }
})
watch(()=>formData.star,()=>{
    if(formData.star === 0){
        starR.value = true
    }else{
        starR.value = false
    }
})
onMounted(()=>{
    document.title = store.state.user.language.loading
    store.dispatch('loadproduct',props.slug).then(()=>{
        product.value = store.state.prs.product[0]
        formData.product_id = product.value.id
        kartof.id = product.value.id
        setCat()
        informations.title = store.state.prs.product[1].slice(0,4)
        informations.body = store.state.prs.product[0].infbody.slice(0,4)
        setTitle()
        setBody()
        stock.value =  store.state.user.language.availability.replace('{x}',product.value.quantity)
        loadReview(product.value.id)
    })
})
const addReview = () => {
    if(store.state.user.isLoggedIn){
        if(formData.body != ''){
            if(formData.star != 0){
                revProccess.value = true
                store.dispatch('addReview',formData).then(()=>{
                    product.value = store.state.prs.product[0]
                    setCat()
                    informations.title = store.state.prs.product[1].slice(0,4)
                    informations.body = store.state.prs.product[0].infbody.slice(0,4)
                    setTitle()
                    setBody()
                    stock.value =  store.state.user.language.availability.replace('{x}',product.value.quantity)
                    loadReview(product.value.id)
                    revProccess.value = false
                })
            }else{
                starR.value = true
            }
        }else{
            bodyR.value = true
        }
    }else{
        dejj()
    }
}
const deleteReview = () =>{
    if(formData.updating){
        formData.deleting = true
        revProccess.value = true
        store.dispatch('addReview',formData).then(()=>{
            formData.updating = null
            formData.deleting = null
            formData.body = ''
            formData.star = 0
            product.value = store.state.prs.product[0]
            setCat()
            informations.title = store.state.prs.product[1].slice(0,4)
            informations.body = store.state.prs.product[0].infbody.slice(0,4)
            setTitle()
            setBody()
            stock.value =  store.state.user.language.availability.replace('{x}',product.value.quantity)
            loadReview(product.value.id)
            revProccess.value = false
        })
    }
}
watch(()=>store.state.user.isLoggedIn,()=>{
    if(product.value){
        loadReview(product.value.id)
    }
})
const loadReview = (id) => {
    if(product.value){
        if(store.state.user.isLoggedIn){
            store.dispatch('loadReview',kartof).then(()=>{
                if(store.state.user.review){
                    formData.body = store.state.user.review.body
                    formData.star = store.state.user.review.star
                    formData.updating = true
                }
                reviewLoading.value = false
            })
        }else{
            reviewLoading.value = false
        }
    }
}
watch(()=>store.state.user.language,()=>{
    if(product.value){
        stock.value =  store.state.user.language.availability.replace('{x}',product.value.quantity)
        setCat()
        if(informations.title){
            setTitle()
        }
        if(informations.body){
            setBody()
        }
    }

})
const setTitle = () => {
    if (localStorage.getItem('lang') === 'az'){
        informations.title.forEach(item => {
            item.title = item.translations[0].title
        });
    }else if (localStorage.getItem('lang') === 'en'){
        informations.title.forEach(item => {
            item.title = item.translations[1].title
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        informations.title.forEach(item => {
            item.title = item.translations[2].title
        });
    }
}
const setBody = () => {
    if (localStorage.getItem('lang') === 'az'){
        informations.body.forEach(item => {
            item.body = item.translations[0].body
        });
    }else if (localStorage.getItem('lang') === 'en'){
        informations.body.forEach(item => {
            item.body = item.translations[1].body
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        informations.body.forEach(item => {
            item.body = item.translations[2].body
        });
    }
}
const setCat = () => {
    if (localStorage.getItem('lang') === 'az'){
        product.value.category.name = product.value.category.translations[0].name
        product.value.name = product.value.translations[0].name
        document.title = product.value.name
    }else if (localStorage.getItem('lang') === 'en'){
        product.value.category.name = product.value.category.translations[1].name
        product.value.name = product.value.translations[1].name
        document.title = product.value.name
    }else if (localStorage.getItem('lang') === 'ru'){
        product.value.category.name = product.value.category.translations[2].name
        product.value.name = product.value.translations[2].name
        document.title = product.value.name
    }
}
const showMore = () => {
    showingMore.value = true
    informations.title = store.state.prs.product[1]
    informations.body = store.state.prs.product[0].infbody
    setTitle()
    setBody()
}
const showLess = () => {
    showingMore.value = false
    informations.title = store.state.prs.product[1].slice(0,4)
    informations.body = store.state.prs.product[0].infbody.slice(0,4)
    setTitle()
    setBody()
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
