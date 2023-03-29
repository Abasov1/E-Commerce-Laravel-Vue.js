<template>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4 position-relative">
                <div v-if="productSending" style="position:absolute;z-index:99999;width:100%;height:100%;background-color:white;opacity:50%;"></div>
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Product input</h5> <small class="text-muted float-end">netersen gulenver</small>
            </div>
            <div class="card-body">
                <form @submit.prevent="makeProduct">
                    <div v-if="previewUrl" class="row mb-3">
                            <label class="col-sm-2 mt-auto mb-auto col-form-label" for="basic-default-name">Preview</label>
                            <div class="col-sm-10">
                                <div class="position-relative d-flex justify-content-center" style="width:30%;">
                                    <img :src="previewUrl" style="width:80%;">
                                    <a v-if="filePreview.imagescount > filePreview.page + 1" @click.prevent="nextImageUp" href="#" class="position-absolute top-50 end-0 translate-middle-y">
                                            <i class="bx bxs-chevron-right" style="font-size: 2rem;"></i>
                                        </a>
                                    <a v-if="filePreview.page != 0" @click.prevent="previousImageUp" href="#" class="position-absolute top-50 start-0 translate-middle-y">
                                        <i class="bx bxs-chevron-left" style="font-size: 2rem;"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="formFile" class="col-sm-2 col-form-label" >Brand Image</label>
                            <div class="col-sm-10">
                                <input ref="fileInput"  multiple @change="setImage" class="form-control" type="file" id="formFile">
                            </div>
                        </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-name">Name <b v-if="selected.id && !v$.name.$error" style="color:yellow;"> - updating</b><b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                        <div class="col-sm-10">
                        <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-company">Merchant <b v-if="selected.id && !v$.merchant_id.$error && !selected.merchant_id" style="color:yellow;"> - updating</b> <b v-if="v$.merchant_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetMerchant" v-if="selected.merchant_id">reset</b></label>
                        <div class="col-sm-10">
                        <input v-model='form.merchant' v-debounce:300ms="searchMerchant" type="text" class="form-control" :class="[v$.merchant_id.$error ? 'is-invalid' : '']" id="basic-default-company" placeholder="ACME Inc." />
                        <span v-if="!selected.merchant_id" @click="chooseMerchant">{{merchant.name}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-email">Brand <b v-if="selected.id && !v$.brand_id.$error && !selected.brand_id" style="color:yellow;"> - updating</b> <b v-if="v$.brand_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetBrand" v-if="selected.brand_id">reset</b></label>
                        <div class="col-sm-10">
                        <input v-debounce:300ms="searchBrand" v-model="form.brand" type="text" class="form-control" :class="[v$.brand_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        <span v-if="!selected.brand_id" @click="chooseBrand">{{brand.name}}</span>
                    </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone">Category <b v-if="selected.id && !v$.category_id.$error && !selected.category_id" style="color:yellow;"> - updating</b> <b v-if="v$.category_id.$error" style="color:rgb(255,62,29)"> - required</b><b style="cursor:pointer;color:blue;" @click="resetCategory" v-if="selected.category_id">reset</b></label>
                        <div class="col-sm-10">
                            <input v-model="form.category" v-debounce:300ms="searchCategory" type="text" class="form-control" :class="[v$.category_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if="!selected.category_id" @click="chooseCategory">{{category.name}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-name">Price  <b v-if="selected.id && !v$.price.$error" style="color:yellow;"> - updating</b><b v-if="v$.price.$error" style="color:rgb(255,62,29)"> - required</b></label>
                        <div class="col-sm-10">
                        <input v-model="selected.price" type="text" class="form-control" :class="[v$.price.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation <b v-if="v$.inf.$error" style="color:rgb(255,62,29)"> - required</b></label>
                        <div class="col-sm-10">
                        <div class="row">
                            <div class="col">
                                <input v-model="info.title" class="form-control" :class="[d$.title.$error ? 'is-invalid' : '']" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                <span style="color:rgb(255,62,29)" v-if="d$.title.$error">{{d$.title.$errors[0].$message}}</span>
                            </div>
                            <div class="col">
                                <input v-model="info.body" class="form-control" :class="[d$.body.$error ? 'is-invalid' : '']" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                <span style="color:rgb(255,62,29)" v-if="d$.body.$error">{{d$.body.$errors[0].$message}}</span>
                            </div>
                            <div class="col">
                                <button v-if="!info.updating" @click="addinf" type="button" class="btn btn-primary ms-2">Add İnformation</button>
                                <button v-if="info.updating" @click="updateInf" type="button" class="btn btn-primary ms-2">Update İnformation</button>
                                <button v-if="info.updating" @click="resetInfEdit" type="button" class="btn btn-primary ms-2">X</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row mb-3" style="display: flex;flex-wrap: wrap;justify-content: flex-start">
                        <div class="col-6" style="flex-basis: calc(100% / {{infoCount}});flex: 1 1 50%;" v-if="selected.inf" v-for="(inf,index) in selected.inf" :key="inf.title">
                            <label><b>{{inf.title}}: </b> {{inf.body}} <i @click="deleteInf(inf.title)" class="bx bxs-trash"></i><i @click='editInf(inf.title,inf.body)' class="bx bxs-edit"></i></label>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                        <button class="btn btn-primary">Send</button>
                        <button @click="reset" type="button" class="btn btn-primary ms-2">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <div class="card pt-4">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        <label>Show</label>
                                    </div>
                                    <div class="col-4 d-flex justify-content-center align-items-center">
                                        <select v-model="page.perPage" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select">
                                            <option value="1">1</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="75">75</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="col-6 d-flex justify-content-start" >
                                        entries
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                            <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        <label>Search:</label>
                                    </div>
                                    <div class="col-10">
                                        <input v-model="page.search" v-debounce:400ms="searchProducts" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="datatables-basic table border-top">
                    <thead>
                        <tr>
                        <th>id</th>
                        <th @click="order" class="d-flex justify-content-between">Name <i style="solor:rgb(187,195,204)" :class="[page.orderBy ? 'bx bxs-chevron-up':'bx bxs-chevron-down']"></i></th>
                        <th>Category</th>
                        <th>Merchant</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td class="dt-checkboxes-cell">
                                <input type="checkbox" class="dt-checkboxes form-check-input">
                            </td>
                            <td  @click="showProduct(product.name,product.category.name,product.merchant.name,product.brand.name,product.price,product.informations,product.images)" style="cursor:pointer;overflow:hidden;">
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            <span v-if="product.images === 'default.png'" class="avatar-initial rounded-circle bg-label-warning">{{product.name.charAt(0)}}</span>
                                            <img v-else :src="'http://127.0.0.1:8000/api/images/'+product.images[0].image" alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column" style="overflow:hidden;max-width:100px;">
                                        <span class="emp_name text-truncate">{{product.name}}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{product.category.name}}</td>
                            <td>{{product.merchant.name}}</td>
                            <td>{{product.brand.name}}</td>
                            <td>{{product.price}}</td>
                            <td>
                                <a @click.prevent="deletE(product.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                                <a @click.prevent="editE(product.id,product.name,product.category.id,product.category.name,product.merchant.id,product.merchant.name,product.brand.id,product.brand.name,product.price,product.informations)" href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>

                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="row" style="padding-left:40px;padding-top:20px;">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{page.page}} to {{page.perPage}} of {{productCount}} brands</div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous" :class="[page.page === 1 ? 'disabled' : '']" id="DataTables_Table_0_previous">
                                        <a @click.prevent="previousPage" href="#" aria-controls="DataTables_Table_0" data-dt-idx="previous" tabindex="0" class="page-link">Previous</a>
                                    </li>
                                    <li v-for="n in pagesCount" :key="n"  class="paginate_button page-item active">
                                        <a @click.prevent="changePage(n)" v-show="n < page.page + 5 && page.page < n+2 && n != pagesCount" href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link" :style="[page.page == n ? 'background-color:rgb(105,108,255);color:white;' : 'background-color:rgb(240,242,244);color:rgb(105,122,141);']">{{n}}</a>
                                    </li>
                                    <li v-if='pagesCount - page.page > 5' class="paginate_button page-item disabled" id="DataTables_Table_0_ellipsis">
                                        <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="ellipsis" tabindex="0" class="page-link">…</a>
                                    </li>
                                    <li class="paginate_button page-item ">
                                        <a @click.prevent="changePage(pagesCount)" href="#" aria-controls="DataTables_Table_0" data-dt-idx="14" tabindex="0" class="page-link" :style="[page.page === pagesCount ? 'background-color:rgb(105,108,255);color:white;' : 'background-color:rgb(240,242,244);color:rgb(105,122,141);']">{{pagesCount}}</a>
                                    </li>
                                    <li class="paginate_button page-item next" :class="[page.page === pagesCount ? 'disabled' : '']" id="DataTables_Table_0_next">
                                        <a @click.prevent="nextPage" href="#" aria-controls="DataTables_Table_0" data-dt-idx="next" tabindex="0" class="page-link">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="previewProduct" class="container fixed-top fixed-bottom d-flex justify-content-center align-items-center">
            <div class="card text-center position-relative shadow-lg" style="width:70%;height:80%">
                <button @click="closePreview" type="button" class="close position-absolute top-0 end-0 m-3" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                <div class="card-body">
                    <h3 class="card-title">{{previewMaterial.name}}</h3>
                    <div class="row" style="height: 50%;">
                        <div class="col-6" style="height: 100%;">
                            <div class="row d-flex justify-content-left align-items-start" style="height: 100%;">
                                <div class="col-12 position-relative" style="width:90%;">
                                    <img :src="'http://127.0.0.1:8000/api/images/'+previewMaterial.images[previewMaterial.page].image" style="width:80%;" alt="Your Image" class="img-fluid">

                                    <a v-if="previewMaterial.page + 1 < previewMaterial.imagescount" @click.prevent="nextImage" href="#" class="position-absolute top-50 end-0 translate-middle-y">
                                        <i class="bx bxs-chevron-right" style="font-size: 2rem;"></i>
                                    </a>
                                    <a v-if="previewMaterial.page != 0" @click.prevent="previousImage" href="#" class="position-absolute top-50 start-0 translate-middle-y">
                                        <i class="bx bxs-chevron-left" style="font-size: 2rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="d-flex justify-content-left align-items-start">Category: {{previewMaterial.category}}</h5>
                            <h5 class="d-flex justify-content-left align-items-start">Brand: {{previewMaterial.brand}}</h5>
                            <h5 class="d-flex justify-content-left align-items-start">Merchant: {{previewMaterial.merchant}}</h5>
                            <h5 class="d-flex justify-content-left align-items-start">Price: {{previewMaterial.price}}</h5>
                        </div>
                    </div>
                    <h4 class="card-title">Information</h4>
                    <div class="row">
                        <div class="col-6" style="flex-basis: calc(100% / {{infoCount}});flex: 1 1 50%;" v-if="previewMaterial.inf" v-for="inf in previewMaterial.inf" :key="inf.title">
                            <h5><b>{{inf.title}}: </b> {{inf.body}}</h5>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
</template>


<script setup>
import { ref,reactive,watch,onMounted } from 'vue'
import store from '../../store'
import axios from 'axios'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
const merchant = ref('')
const brand = ref('')
const category = ref('')
const loopNumber = ref(0)
const previewUrl = ref('')
const previewProduct = ref(false)
const productSending = ref(false)
const fileInput = ref(null)
const products = ref([])
const infoCount = ref('')
const pagesCount = ref('')
const productCount = ref('')
const page = reactive({
        page:1,
        search:'',
        perPage:'10',
        order:false,
        orderBy:true
    })
    watch(()=>page.perPage,()=>{
        page.page = 1
        loadProducts()
    })
    const previewMaterial = reactive({
        name:'',
        merchant:'',
        brand:'',
        category:'',
        price:'',
        inf:[],
        images:null,
        imagescount:null,
        page:0
    })
    const filePreview = reactive({
        page:0  ,
        imagescount:0,
        images:null
    })
    const form = reactive({
        merchant:'',
        brand:'',
        category:''
    })
    const info = reactive({
        title:'',
        body:'',
        updating:false,
        updateIndex:false
    })
    const selected = reactive({
        images:null,
        name:'',
        id:'',
        merchant_id:'',
        brand_id:'',
        category_id:'',
        price:'',
        inf:[]
    })
    const rules = {
        name:{required},
        merchant_id:{required},
        brand_id:{required},
        category_id:{required},
        price:{required},
        inf:{required}
    }
    const infrules = {
        title:{required},
        body:{required},
    }
    onMounted(async()=>{
        loadProducts()
    })
    const v$ = useVuelidate(rules,selected)

    const d$ = useVuelidate(infrules,info)

    const addinf = () => {
        d$.value.$validate()
        if(d$.value.$error){
            return
        }
             if(selected.inf.some(infos => infos.title === info.title)){
                alert('title already exists')
            }else{
                selected.inf.push({title:info.title,body:info.body})
                infoCount.value = selected.inf.length
            }
        }
    const updateInf = () => {
        if(info.updating){
            if(selected.inf.some(infos => infos.title === info.title && infos.title != info.updating)){
                alert('title already exists')
            }else{
                selected.inf[info.updateIndex].title = info.title
                selected.inf[info.updateIndex].body = info.body
                resetInfEdit()
            }
        }
    }
    const editInf = (title,body) => {
        info.updating = title
        info.title = title
        info.body = body
        info.updateIndex = selected.inf.findIndex(item => item.title === title);
    }

    const deleteInf = (title) => {
        const index = selected.inf.findIndex(item => item.title === title);
        if (index !== -1) {
            selected.inf.splice(index, 1);
            infoCount.value = selected.inf.length
        }
    }

    const showProduct = (name,categoryname,merchantname,brandname,price,informations,images) => {
        previewProduct.value = true
        previewMaterial.name = name
        previewMaterial.category = categoryname
        previewMaterial.merchant = merchantname
        previewMaterial.brand = brandname
        previewMaterial.price = price
        previewMaterial.inf = informations
        previewMaterial.images = images
        previewMaterial.imagescount = images.length
    }
    const closePreview = () => {
        previewProduct.value = false
        previewMaterial.name = ''
        previewMaterial.category = ''
        previewMaterial.merchant = ''
        previewMaterial.brandname = ''
        previewMaterial.price = ''
        previewMaterial.inf = ''
        previewMaterial.images = null
        previewMaterial.imagescount = null
        previewMaterial.page = 0
    }
    const nextImage = () => {
        if(previewMaterial.imagescount > previewMaterial.page + 1){
            previewMaterial.page++
        }
    }
    const previousImage = () => {
        if(previewMaterial.page != 0){
            previewMaterial.page--
        }
    }
    const nextImageUp = () => {
        if(filePreview.imagescount > filePreview.page + 1){
            filePreview.page++
            setImage({ target: { files: selected.images } });
        }
    }
    const previousImageUp = () => {
        if(filePreview.page != 0){
            filePreview.page--
            setImage({ target: { files: selected.images } });
        }
    }
    const reset = () => {
        merchant.value = ""
        brand.value = ""
        category.value = ""
        form.merchant = ""
        form.brand = ""
        form.category = ""
        selected.name = ""
        selected.merchant_id = ""
        selected.brand_id = ""
        selected.category_id = ""
        selected.price = ""
        selected.id = ""
        selected.inf = []
        info.updating = false
        info.title = ""
        info.body = ""
        fileInput.value.value = ""
        selected.images = null
        filePreview.imagescount = null
        filePreview.page = 0
        previewUrl.value = false
        v$.value.$reset()
        d$.value.$reset()
    }
    const resetInfEdit = () => {
        info.updating = false
        info.title = ""
        info.body = ""
        d$.value.$reset()
    }
    const setImage = (event) => {
        selected.images = event.target.files
        filePreview.imagescount = event.target.files.length
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(event.target.files[filePreview.page]);
    }
    const makeProduct = () => {
        v$.value.$validate()
        if(v$.value.$error){
            return
        }
        productSending.value = true
        const formData = new FormData()
        for (let i = 0; i < selected.images.length; i++) {
          formData.append('images[]', selected.images[i]);
        }
        formData.append('name',selected.name)
        formData.append('id',selected.id)
        formData.append('category_id',selected.category_id)
        formData.append('merchant_id',selected.merchant_id)
        formData.append('brand_id',selected.brand_id)
        formData.append('price',selected.price)
        const infJson = JSON.stringify(selected.inf)
        formData.append('inf',infJson)
        if(window.confirm('Are you sure to make product')){
            store.dispatch('addproduct',formData).then(()=>{
                reset()
                productSending.value = false
                loadProducts()
            }).catch(err=>{
                productSending.value = false
            })
        }else{
            productSending.value = false
        }
    }
    const searchMerchant = async() => {
            await axios.post('http://127.0.0.1:8000/api/merchants',form,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                if(form.merchant){
                    if(selected.merchant_id === ''){
                        merchant.value = response.data.merchant
                    }
                }
            })
        }
    const chooseMerchant = () => {
        if(merchant.value){
            selected.merchant_id = merchant.value.id
            form.merchant = merchant.value.name
            merchant.value = ''
        }
    }
    const resetMerchant = () => {
        selected.merchant_id = ''
    }
    const searchBrand = async() => {
        if(form.brand){
                await axios.post('http://127.0.0.1:8000/api/brands',form,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    if(form.brand){
                        brand.value = response.data.brand
                    }
                })
            }else{
                brand.value = ''
            }
    }
    const chooseBrand = () => {
        if(brand.value){
            selected.brand_id = brand.value.id
            form.brand = brand.value.name
            brand.value = ''
        }
    }
    const resetBrand = () => {
        selected.brand_id = ''
    }
    const searchCategory = async() => {
        if(form.category){
                await axios.post('http://127.0.0.1:8000/api/categorys',form,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    category.value = response.data.category
                })
            }else{
                category.value = ''
            }
    }
    const chooseCategory = () => {
        if(category.value){
            selected.category_id = category.value.id
            form.category = category.value.name
            category.value = ''
        }
    }
    const deletE = async(id) => {
        if(window.confirm('Are you sure you want to delete?')){
            await axios.post('http://127.0.0.1:8000/api/deleteproduct/'+id,null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    loadProducts()
                    alert(response.data.message)
                })
        }
    }
    const editE = (id,name,categoryid,categoryname,merchantid,merchantname,brandid,brandname,price,informations) => {
        selected.id = id
        selected.name = name
        selected.category_id = categoryid
        form.category = categoryname
        selected.merchant_id = merchantid
        form.merchant = merchantname
        selected.brand_id = brandid
        form.brand = brandname
        selected.price = price
        selected.inf = informations
        infoCount.value = selected.inf.length
        v$.value.$reset()
        d$.value.$reset()
    }
    const searchProducts = () => {
        page.page = 1
        loadProducts()
    }
    const order = () => {
        page.order = true
        loadProducts()
        page.orderBy = !page.orderBy
    }
    const loadProducts = async() => {
        await axios.post('http://127.0.0.1:8000/api/loadproducts',page,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    products.value = response.data.products.data
                    pagesCount.value = response.data.last
                    productCount.value = response.data.count
                })
    }
    const resetCategory = () => {
        selected.category_id = ''
    }
    const changePage = (n) => {
        page.page = n
        loadProducts()
    }
    const previousPage = () => {
        if(page.page != 1){
            page.page--
            loadProducts()
        }
    }
    const nextPage = () => {
        if(page.page != pagesCount){
            page.page++
            loadProducts()
        }
    }
</script>

<style scoped>
.modalcard{
    position:fixed;
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999999999;
    padding:20px;
}
</style>
