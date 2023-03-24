<template>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Product input</h5> <small class="text-muted float-end">netersen gulenver</small>
            </div>
            <div class="card-body">
                <form @submit.prevent="makeProduct">
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-name">Name <b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                        <div class="col-sm-10">
                        <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-company">Merchant  <b v-if="v$.merchant_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetMerchant" v-if="selected.merchant_id">reset</b></label>
                        <div class="col-sm-10">
                        <input v-model='form.merchant' v-debounce:700ms="searchMerchant" type="text" class="form-control" :class="[v$.merchant_id.$error ? 'is-invalid' : '']" id="basic-default-company" placeholder="ACME Inc." />
                        <span v-if="!selected.merchant_id" @click="chooseMerchant">{{merchant.name}}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-email">Brand  <b v-if="v$.brand_id.$error" style="color:rgb(255,62,29)"> - required</b> <b style="cursor:pointer;color:blue;" @click="resetBrand" v-if="selected.brand_id">reset</b></label>
                        <div class="col-sm-10">
                        <input v-debounce:700ms="searchBrand" v-model="form.brand" type="text" class="form-control" :class="[v$.brand_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        <span v-if="!selected.brand_id" @click="chooseBrand">{{brand.name}}</span>
                    </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone">Category  <b v-if="v$.category_id.$error" style="color:rgb(255,62,29)"> - required</b><b style="cursor:pointer;color:blue;" @click="resetCategory" v-if="selected.category_id">reset</b></label>
                        <div class="col-sm-10">
                            <input v-model="form.category" v-debounce:700ms="searchCategory" type="text" class="form-control" :class="[v$.category_id.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if="!selected.category_id" @click="chooseCategory">{{category.name}}</span>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-name">Price <b v-if="v$.price.$error" style="color:rgb(255,62,29)"> - required</b></label>
                        <div class="col-sm-10">
                        <input v-model="selected.price" type="text" class="form-control" :class="[v$.price.$error ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation</label>
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
                                <button @click="addinf" type="button" class="btn btn-primary ms-2">Add İnformation</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div v-if="selected.inf" v-for="inf in selected.inf" class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone"><b>{{inf.title}}: </b> {{inf.body}}</label>
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
</div>
</template>


<script setup>
import { ref,reactive,watch } from 'vue'
import store from '../../store'
import axios from 'axios'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
const merchant = ref('')
const brand = ref('')
const category = ref('')
const form = reactive({
    merchant:'',
    brand:'',
    category:''
})
const info = reactive({
    title:'',
    body:''
})
const selected = reactive({
    name:'',
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

}
const makeProduct = () => {
    v$.value.$validate()
    if(v$.value.$error){
        return
    }
    if(window.confirm('Are you sure to make product')){
        store.dispatch('addproduct',selected)
    }else{
        //
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
const resetCategory = () => {
    selected.category_id = ''
}
</script>

<style scoped>

</style>
