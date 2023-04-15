<template>
    <main id="content" role="main" class="cart-page">
            <div class="container">
                <div class="row mb-8">
                    <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                        <div style="display:none" ref="comeBackTop"></div>
                        <!-- List -->
                        <div v-if="categories && categories[0].length != 0" ref="comeBackTop" class="mb-8 border border-width-2 border-color-3 borders-radius-6">
                            <!-- List -->
                            <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
                                <li v-for="category in categories[0]" :key="category.id">
                                        <a class="dropdown-current active" href="#">{{category.name}}<span class="text-gray-25 font-size-12 font-weight-normal"></span></a>

                                        <ul class="list-unstyled dropdown-list">
                                            <li v-for="cat in category.subcategories" :key="cat.id"><a v-if="categories[1].includes(cat.id)" @click.prevent="formData.category && formData.category === cat.id ? formData.category = null : formData.category = cat.id" class="dropdown-item" :style="[cat.id === formData.category ? 'color:yellow' : '']" href="#">{{cat.name}}</a></li>
                                        </ul>
                                </li>
                            </ul>
                            <!-- End List -->
                        </div>
                        <!-- Filter -->
                        <div class="mb-6">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">{{store.state.user.language.filters}}</h3>
                            </div>
                            <div class="border-bottom pb-4 mb-4">
                                <h4 class="font-size-14 mb-3 font-weight-bold">{{store.state.user.language.merchants}}</h4>

                                <!-- Checkboxes -->
                                <div v-if="showBras" v-for="bra in merchants" :key="bra.id" class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input v-model="merchantCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'asdf'+bra.id">
                                        <label class="custom-control-label" :for="'asdf'+bra.id">{{bra.name}}
                                        </label>
                                    </div>
                                </div>
                                <div v-if="showQras" v-for="bra in merchants" :key="bra.id" class="collapse" id="collapseBrand">
                                    <div class="form-group d-flex align-items-center justify-content-between mb-2 pb-1">
                                        <div class="custom-control custom-checkbox">
                                            <input v-model="merchantCheckbox" :value="bra.id" type="checkbox" class="custom-control-input" :id="'asdfg'+bra.id">
                                            <label class="custom-control-label" :for="'asdfg'+bra.id">{{bra.name}}</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Link -->
                                <a v-if="store.state.merchants.mras.length > 4" class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="false" aria-controls="collapseBrand">
                                    <span class="link__icon text-gray-27 bg-white">
                                        <span class="link__icon-inner">+</span>
                                    </span>
                                    <span @click.prevent="showMoreMerchants" v-if="!showQras" class="link-collapse__default">{{store.state.user.language.show_all}}</span>
                                    <span @click.prevent="showLessMerchants" v-if="showQras" class="link-collapse__default">{{store.state.user.language.show_less}}</span>
                                </a>
                                <!-- End Link -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-wd-9gdot5">
                        <!-- Shop-control-bar Title -->
                        <div class="d-block d-md-flex flex-center-between mb-3">
                            <h3 v-if="brand != null" class="font-size-25 mb-2 mb-md-0">{{replacable}}</h3>
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
                        <div class="tab-content">
                            <div class="tab-pane fade pt-2 show active row">
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
        </main>
</template>
<script setup>
const props = defineProps({
    slug:String
})
import store from '../store'
import Product from '../components/Product.vue'
import { onMounted,ref,reactive,computed,watch,getCurrentInstance,onBeforeUnmount} from 'vue'
import {useRouter} from 'vue-router'
const router = useRouter()
const categories = ref(false)
const bruh = ref([])
const merchants = ref([])
const merchantCheckbox = ref([])
const replacable = ref(false)
watch(()=>merchantCheckbox.value,()=>{
    formData.merchants = merchantCheckbox.value
    console.log(formData.merchants)
    formData.page = 1
    loadProducts()
})
const products = ref([])
const show = ref(false)
const showBras = ref(false)
const showQras = ref(false)
const sortDropdown = ref(false)
const perPageDropdown = ref(false)
const comeBackTop = ref(null)
const formData = reactive({
    merchants:null,
    category:null,
    page:1,
    brid:null,
    orderBy:'1'
})
watch(()=>formData.category,()=>{
    formData.page = 1
    loadProducts()
})
watch(()=>formData.orderBy,()=>{
    formData.page = 1
    loadProducts()
})
onMounted(()=>{
    comeBackTop.value.scrollIntoView();
    document.title = store.state.user.language.loading
    replacable.value = store.state.user.language.loading
    store.dispatch('loadbrand',props.slug).then(()=>{
        replacable.value = store.state.user.language.replacable.replace('{x}',store.state.brand.name)
        document.title = replacable.value
        formData.brid = store.state.brand.id
        showBras.value = true
        merchants.value = store.state.merchants.mras.slice(0,4)
        categories.value = store.state.categories
        setCategory()
        loadProducts()
    })
})
watch(()=>store.state.user.language,()=>{
    if(categories.value){
        setCategory()
    }
    if(products.value){
        setPrName()
    }
    if(store.state.brand.name){
        replacable.value = store.state.user.language.replacable.replace('{x}',store.state.brand.name)
        document.title = replacable.value
    }
})
const setCategory = () =>{
        if (localStorage.getItem('lang') === 'az'){
            categories.value[0].forEach(item => {
                item.name = item.translations[0].name
                if(item.subcategories){
                    item.subcategories.forEach(subcat => {
                        subcat.name = subcat.translations[0].name
                    });
                }
            });
        }else if (localStorage.getItem('lang') === 'en'){
            categories.value[0].forEach(item => {
                item.name = item.translations[1].name
                if(item.subcategories){
                    item.subcategories.forEach(subcat => {
                        subcat.name = subcat.translations[1].name
                    });
                }
            });
        }else if (localStorage.getItem('lang') === 'ru'){
            categories.value[0].forEach(item => {
                item.name = item.translations[2].name
                if(item.subcategories){
                    item.subcategories.forEach(subcat => {
                        subcat.name = subcat.translations[2].name
                    });
                }
            });
        }
}
const showMoreMerchants = () => {
        showBras.value = true
        merhants.value = store.state.merchants.mras
        showQras.value = true
}
const showLessMerchants = () => {
        showQras.value = false
        merhants.value = store.state.merchants.mras.slice(0,4)
        showBras.value = true
}
const changePage = (n) => {
    formData.page = n
    products.value = null
    loadProducts()
    comeBackTop.value.scrollIntoView();
}
const loadProducts = () => {
    store.dispatch('loadbrpras',formData).then(()=>{
        products.value = store.state.prs.pras.products
        setPrName()
    })
}
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
const att = (id) => {
    if(bruh.value.includes(id)){
        return false
    }else{
        bruh.value.push(id)
        return true;
    }
}
const noldu = (id) => {
    if(bruh.value.includes(id)){
        return false
    }else{
        return true
    }
}
const brand = computed(()=>store.state.brand)

</script>
