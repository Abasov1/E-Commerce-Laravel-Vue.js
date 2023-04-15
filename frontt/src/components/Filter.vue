<template>
            <div class="container">
                <div class="row mb-8">
                    <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                        <!-- List -->
                       
                        <!-- Filter -->
                        <div class="mb-6">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 ref="comeBackTop" class="section-title section-title__sm mb-0 pb-2 font-size-18">{{store.state.user.language.filters}}</h3>
                            </div>
                            <div class="border-bottom pb-4 mb-4">
                                <h4 class="font-size-14 mb-3 font-weight-bold">{{store.state.user.language.brands}}</h4>

                                <!-- Checkboxes -->
                                <div v-if="showBras" v-for="bra in brands" :key="bra.id" class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input v-model="brandCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'asdf'+bra.id">
                                        <label class="custom-control-label" :for="'asdf'+bra.id">{{bra.name}}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="showQras" v-for="bra in brands" :key="bra.id" class="collapse" id="collapseBrand">
                                    <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input v-model="brandCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'asdfg'+bra.id">
                                            <label class="custom-control-label" :for="'asdfg'+bra.id">{{bra.name}}</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Link -->
                                <a v-if="store.state.brands.bras.length > 3" class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="false" aria-controls="collapseBrand">
                                    <span class="link__icon text-gray-27 bg-white">
                                        <span class="link__icon-inner">+</span>
                                    </span>
                                    <span @click.prevent="showMoreBrands" v-if="!showQras" class="link-collapse__default">{{store.state.user.language.show_all}}</span>
                                    <span @click.prevent="showLessBrands" v-if="showQras" class="link-collapse__default">{{store.state.user.language.show_less}}</span>
                                </a>
                                <!-- End Link -->
                            </div>
                            <div class="border-bottom pb-4 mb-4">
                                <h4 class="font-size-14 mb-3 font-weight-bold" @click="niyee">{{store.state.user.language.merchants}}</h4>

                                <!-- Checkboxes -->
                                <div v-if="showMras" v-for="bra in merchants" :key="bra.id" class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input v-model="merchantCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'merr'+bra.id">
                                        <label class="custom-control-label" :for="'merr'+bra.id">{{bra.name}}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="showNras" v-for="bra in merchants" :key="bra.id" class="collapse" id="collapseBrand">
                                    <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input v-model="merchantCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'merch'+bra.id">
                                            <label class="custom-control-label" :for="'merch'+bra.id">{{bra.name}}</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Link -->
                                <a v-if="store.state.merchants.mras.length > 3" class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="false" aria-controls="collapseBrand">
                                    <span class="link__icon text-gray-27 bg-white">
                                        <span class="link__icon-inner">+</span>
                                    </span>
                                    <span @click.prevent="showMoreMerchants" v-if="!showNras" class="link-collapse__default">{{store.state.user.language.show_all}}</span>
                                    <span @click.prevent="showLessMerchants" v-if="showNras" class="link-collapse__default">{{store.state.user.language.show_less}}</span>
                                </a>
                                <!-- End Link -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-wd-9gdot5">
                        <!-- Shop-control-bar Title -->
                        <div class="d-block d-md-flex flex-center-between mb-3">
                            <h3 class="font-size-25 mb-2 mb-md-0">{{categories[0].parent.name}}</h3>
                            <p class="font-size-14 text-gray-90 mb-0">Showing 1–25 of 56 results</p>
                        </div>
                        <!-- End shop-control-bar Title -->
                        <!-- Shop-control-bar -->
                        <div class="bg-gray-1 flex-center-between borders-radius-9 py-1">
                            <div class="d-xl-none">
                                <!-- Account Sidebar Toggle Button -->
                                <a id="sidebarNavToggler1" class="btn btn-sm py-1 font-weight-normal" href="javascript:;" role="button"
                                    aria-controls="sidebarContent1"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    data-unfold-event="click"
                                    data-unfold-hide-on-scroll="false"
                                    data-unfold-target="#sidebarContent1"
                                    data-unfold-type="css-animation"
                                    data-unfold-animation-in="fadeInLeft"
                                    data-unfold-animation-out="fadeOutLeft"
                                    data-unfold-duration="500">
                                    <i class="fas fa-sliders-h"></i> <span class="ml-1" id="topik">{{store.state.user.language.filters}}</span>
                                </a>
                                <!-- End Account Sidebar Toggle Button -->
                            </div>
                            <div class="d-flex">
                                <select v-model="formData.orderBy">
                                    <option value="1">{{store.state.user.language.filter.newest}}</option>
                                    <option value="2">{{store.state.user.language.filter.oldest}}</option>
                                    <option value="3">{{store.state.user.language.filter.h_first}}</option>
                                    <option value="4">{{store.state.user.language.filter.l_first}}</option>
                                    <option value="5">A-Z</option>
                                    <option value="6">Z-A</option>
                                </select>
                            </div>
                            
                        </div>
                        <!-- End Shop-control-bar -->
                        <!-- Shop Body -->
                        <!-- Tab Content -->
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                                <div class="row ml-2" style="background-color: rgba(99, 50, 60, 0);">
                                    <Product v-if="products && !store.state.loading" v-for="pr in products" :key="pr.id" :pr="pr" />    
                                </div>
                            </div>
                        </div>
                        <!-- End Tab Content -->
                        <!-- End Shop Body -->
                        <!-- Shop Pagination -->
                        <nav class="d-md-flex justify-content-between align-items-center border-top pt-3" aria-label="Page navigation example">
                            <div class="text-center text-md-left mb-3 mb-md-0">Showing 1–25 of 56 results</div>
                            <ul class="pagination mb-0 pagination-shop justify-content-center justify-content-md-start">
                                <li @click.prevent="changePage(1)" class="page-item"><a class="page-link" :class="formData.page === 1 ? 'current' : ''" href="#">1</a></li>
                                <li v-if="formData.page > 2" class="page-item"><a class="page-link" href="#">...</a></li>
                                <li v-if="store.state.prs.pras.count" v-for="n in store.state.prs.pras.count" :key="n" class="page-item" @click.prevent="changePage(n)" v-show="n != 1&& n > formData.page-2  && formData.page + 2 > n"><a class="page-link" :class="n === formData.page ? 'current' : ''" href="#">{{n}}</a></li>
                                <li v-if="store.state.prs.pras.count - 2 > formData.page" class="page-item"><a class="page-link" href="#">...</a></li>
                                <li v-if="store.state.prs.pras.count" class="page-item" @click.prevent="changePage(store.state.prs.pras.count)" v-show="formData.page + 2 <= store.state.prs.pras.count"><a class="page-link" href="#">{{store.state.prs.pras.count}}</a></li>
                            </ul>
                        </nav>
                        <!-- End Shop Pagination -->
                    </div>
                </div>
            </div>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
import Product from './Product.vue'
const brands = ref([])
const brandCheckbox = ref([])
watch(()=>brandCheckbox.value,()=>{
    formData.brands = brandCheckbox.value
    formData.page = 1
    loadProducts()

})
const merchants = ref([])
const merchantCheckbox = ref([])
watch(()=>merchantCheckbox.value,()=>{
    formData.merchants = merchantCheckbox.value
    formData.page = 1
    loadProducts()

})
const products = ref([])
const show = ref(false)
const showBras = ref(false)
const showQras = ref(false)
const showMras = ref(false)
const showNras = ref(false)
const sortDropdown = ref(false)
const perPageDropdown = ref(false)
const comeBackTop = ref(null)
const props = defineProps({
    catid:Number,
});
const formData = reactive({
    brands:null,
    merchants:null,
    page:1,
    catid:props.catid,
    orderBy:'1'
});
watch(()=>formData.orderBy,()=>{
    formData.page = 1
    loadProducts()
})
onMounted(()=>{
    comeBackTop.value.scrollIntoView();
    store.dispatch('loadbras',store.state.category[0].parent.id).then(()=>{
        brands.value = store.state.brands.bras.slice(0,3)
        showBras.value = true
    })
    store.dispatch('loadmras',store.state.category[0].parent.id).then(()=>{
        merchants.value = store.state.merchants.mras.slice(0,3)
        showMras.value = true
    })
    loadProducts()
})
const showMoreBrands = () => {
        showBras.value = true
        brands.value = store.state.brands.bras
        showQras.value = true   
}
const showLessBrands = () => {
        showQras.value = false
        brands.value = store.state.brands.bras.slice(0,3)
        showBras.value = true
}
const showMoreMerchants = () => {
        showMras.value = true
        merchants.value = store.state.merchants.mras
        showNras.value = true   
}
const showLessMerchants = () => {
        showNras.value = false
        merchants.value = store.state.merchants.mras.slice(0,3)
        showMras.value = true
}
const changePage = (n) => {
    formData.page = n
    products.value = null
    loadProducts()
    comeBackTop.value.scrollIntoView();

}
const loadProducts = () => {
    store.dispatch('loadpras',formData).then(()=>{
        products.value = store.state.prs.pras.products
        setPrName()
    })
}
watch(()=>store.state.user.language,()=>{
    if(products.value != null){
        setPrName()
    }
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
const categories = computed(()=>store.state.category)

</script>
<style scoped>

</style>