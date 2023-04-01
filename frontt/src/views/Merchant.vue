<template>
            <div class="container">
                <div class="row mb-8">
                    <div class="d-none d-xl-block col-xl-3 col-wd-2gdot5">
                        <!-- List -->
                        <div v-if="categories" ref="comeBackTop" class="mb-8 border border-width-2 border-color-3 borders-radius-6">
                            <!-- List -->
                            <ul id="sidebarNav" class="list-unstyled mb-0 sidebar-navbar">
                                <li v-for="category in categories">
                                    <a class="dropdown-current active" href="#">{{category.parent.name}}<span class="text-gray-25 font-size-12 font-weight-normal"></span></a>

                                    <ul class="list-unstyled dropdown-list">
                                        <li v-for="cat in categories" :key="cat.id"><a v-if="cat.category_id == category.parent.id" @click.prevent="formData.category && formData.category === cat.id ? formData.category = null : formData.category = cat.id" class="dropdown-item" :style="[cat.id === formData.category ? 'color:yellow' : '']" href="#">{{cat.name}}</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <!-- End List -->
                        </div>
                        <!-- Filter -->
                        <div class="mb-6">
                            <div class="border-bottom border-color-1 mb-5">
                                <h3 class="section-title section-title__sm mb-0 pb-2 font-size-18">Filters</h3>
                            </div>
                            <div class="border-bottom pb-4 mb-4">
                                <h4 class="font-size-14 mb-3 font-weight-bold">Brands</h4>

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
                                <a v-if="store.state.brands.bras.length > 4" class="link link-collapse small font-size-13 text-gray-27 d-inline-flex mt-2" data-toggle="collapse" href="#collapseBrand" role="button" aria-expanded="false" aria-controls="collapseBrand">
                                    <span class="link__icon text-gray-27 bg-white">
                                        <span class="link__icon-inner">+</span>
                                    </span>
                                    <span @click.prevent="showMoreBrands" v-if="!showQras" class="link-collapse__default">Show All</span>
                                    <span @click.prevent="showLessBrands" v-if="showQras" class="link-collapse__default">Show less</span>
                                </a>
                                <!-- End Link -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-wd-9gdot5">
                        <!-- Shop-control-bar Title -->
                        <div class="d-block d-md-flex flex-center-between mb-3">
                            <h3 v-if="merchant" class="font-size-25 mb-2 mb-md-0">{{merchant.name}}'s Products</h3>
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
                                    <i class="fas fa-sliders-h"></i> <span class="ml-1" id="topik">Filters</span>
                                </a>
                                <!-- End Account Sidebar Toggle Button -->
                            </div>
                            <div class="d-flex">
                                <form v-if="sortDropdown" method="get">
                                    <!-- Select -->
                                    <div @click="sortDropdown = false" class="dropdown bootstrap-select js-select dropdown-select max-width-200 max-width-160-sm right-dropdown-0 px-2 px-xl-0 show">
                                        <select class="js-select selectpicker dropdown-select max-width-200 max-width-160-sm right-dropdown-0 px-2 px-xl-0" data-style="btn-sm bg-white font-weight-normal py-2 border text-gray-20 bg-lg-down-transparent border-lg-down-0" tabindex="-98">
                                        <option value="one" selected="">Default sorting </option>
                                        <option value="two">Sort by popularity</option>
                                        <option value="three">Sort by average rating</option>
                                        <option value="four">Sort by latest</option>
                                        <option value="five">Sort by price: low to high</option>
                                        <option value="six">Sort by price: high to low</option>
                                        </select>
                                        <button type="button" class="btn dropdown-toggle btn-sm bg-white font-weight-normal py-2 border text-gray-20 bg-lg-down-transparent border-lg-down-0" data-toggle="dropdown" role="button" title="Default sorting" aria-expanded="true">
                                            <div class="filter-option">
                                                <div class="filter-option-inner">
                                                    <div class="filter-option-inner-inner">Default sorting<i class="bi bi-caret-down"></i></div>
                                                </div> 
                                            </div>
                                        </button>
                                        <div class="dropdown-menu show" role="combobox" x-placement="bottom-start" style="max-height: 296.172px; overflow: hidden; min-height: 139px; position: absolute; transform: translate3d(0px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <div class="inner show" role="listbox" aria-expanded="true" tabindex="-1" style="max-height: 264.172px; overflow-y: auto; min-height: 107px;">
                                                <ul class="dropdown-menu inner show">
                                                    <li class="selected active">
                                                        <a role="option" class="dropdown-item selected active" aria-disabled="false" tabindex="0" aria-selected="true">
                                                            <span class=" bs-ok-default check-mark"></span><span class="text">Default sorting</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a role="option" class="dropdown-item" aria-disabled="false" tabindex="0" aria-selected="false">
                                                            <span class=" bs-ok-default check-mark"></span>
                                                            <span class="text">Sort by popularity</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Select -->
                                </form>
                                <form v-else method="get">
                                    <!-- Select -->
                                    <div @click="sortDropdown = true" class="dropdown bootstrap-select js-select dropdown-select max-width-200 max-width-160-sm right-dropdown-0 px-2 px-xl-0">
                                        <button type="button" class="btn dropdown-toggle btn-sm bg-white font-weight-normal py-2 border text-gray-20 bg-lg-down-transparent border-lg-down-0" data-toggle="dropdown" role="button" title="Default sorting">
                                            <div class="filter-option">
                                                <div class="filter-option-inner">
                                                    <div class="filter-option-inner-inner">Default sorting</div>
                                                </div> 
                                            </div>
                                        </button>
                                    </div>
                                    <!-- End Select -->
                                </form>
                            </div>
                            
                        </div>
                        <!-- End Shop-control-bar -->
                        <!-- Shop Body -->
                        <!-- Tab Content -->
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade pt-2 show active" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
                                <ul class="row list-unstyled products-group no-gutters">
                                    <li v-if="products" v-for="pr in products" :key="pr.id" class="col-6 col-md-3 col-wd-2gdot4 product-item">
                                        <div class="product-item__outer h-100">
                                            <div class="product-item__inner px-xl-4 p-3">
                                                <div class="product-item__body pb-xl-2">
                                                    <div class="mb-2"><a href="../shop/product-categories-7-column-full-width.html" class="font-size-12 text-gray-5">{{pr.category.name}}</a></div>
                                                    <h5 class="mb-1 product-item__title"><a href="../shop/single-product-fullwidth.html" class="text-blue font-weight-bold">{{pr.name}}</a></h5>
                                                    <div class="mb-2">
                                                        <a href="../shop/single-product-fullwidth.html" class="d-block text-center"><img class="img-fluid" :src="'http://127.0.0.1:8000/api/images/products/'+pr.images[0].image" alt="Image Description"></a>
                                                    </div>
                                                    <div class="flex-center-between mb-1">
                                                        <div class="prodcut-price">
                                                            <div class="text-gray-100">{{pr.price}}</div>
                                                        </div>
                                                        <div class="d-none d-xl-block prodcut-add-cart">
                                                            <a href="../shop/single-product-fullwidth.html" class="btn-add-cart btn-primary transition-3d-hover"><i class="bi bi-cart"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-item__footer">
                                                    <div class="border-top pt-2 flex-center-between flex-wrap">
                                                        <a href="../shop/wishlist.html" class="text-gray-6 font-size-13"><i class="bi bi-heart mr-1 font-size-15"></i> Wishlist</a>
                                                        <a href="../shop/wishlist.html" class="text-gray-6 font-size-13"><i class="bi bi-bag mr-1 font-size-15"></i> Cart</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
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
                                <li v-if="store.state.prs.pras.count" class="page-item" @click.prevent="changePage(store.state.prs.pras.count)" v-show="formData.page + 2 < store.state.prs.pras.count"><a class="page-link" href="#">{{store.state.prs.pras.count}}</a></li>
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
const categories = ref(false)
const brands = ref([])
const brandCheckbox = ref([])
watch(()=>brandCheckbox.value,()=>{
    formData.brands = brandCheckbox.value
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
const props = defineProps({
    slug:String,
});
const formData = reactive({
    brands:null,
    category:null,
    page:1,
    merid:null
});
watch(()=>formData.category,()=>{
    formData.page = 1
    loadProducts()
})
onMounted(()=>{
    store.dispatch('loadmerchant',props.slug).then(()=>{
        formData.merid = store.state.merchant.id
        showBras.value = true
        brands.value = store.state.brands.bras.slice(0,4)
        categories.value = store.state.categories
        loadProducts()
    })
})
const showMoreBrands = () => {
    store.dispatch('loadbras',store.state.category[0].parent.id).then(()=>{
        showBras.value = true
        brands.value = store.state.brands.bras
        showQras.value = true   
    })
}
const showLessBrands = () => {
        showQras.value = false
        brands.value = store.state.brands.brand1
        showBras.value = true
}
const changePage = (n) => {
    formData.page = n
    products.value = null
    loadProducts()
    comeBackTop.value.scrollIntoView();

}
const loadProducts = () => {
    store.dispatch('loadmrpras',formData).then(()=>{
        products.value = store.state.prs.pras.products
    })
}
const merchant = computed(()=>store.state.merchant)

</script>
<style scoped>
</style>