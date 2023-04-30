<template>
    <div class="container-xxl flex-grow-1 container-p-y" id='topos'>
        <h4 class="fw-bold py-3 mb-4">Categories</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                <div v-if="loading" style="position:absolute;z-index:99999;width:100%;height:100%;background-color:white;opacity:50%;"></div>
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Category input</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeCategory">
                        <div v-if="previewUrl && !updatingUrl" class="row mb-3">
                            <label class="col-sm-2 mt-auto mb-auto col-form-label" for="basic-default-name">Preview</label>
                            <div class="col-sm-10">
                                <img :src="previewUrl">
                            </div>
                        </div>
                        <div v-if="updatingUrl" class="row mb-3">
                            <label class="col-sm-2 mt-auto mb-auto col-form-label" for="basic-default-name">Preview</label>
                            <div class="col-sm-10">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-6">
                                        <img :src="'http://127.0.0.1:8000/api/images/categories/'+updatingUrl" width="300">
                                    </div>
                                    <div class="col-6">
                                        <img v-if="ultiUrl" :src="ultiUrl" width="300">
                                    </div>
                                    <button v-if="ultiUrl" @click="changeImage" type="button" class="btn btn-primary mt-4" style="max-width:100px">Save</button>
                                    <button v-if="ultiUrl" @click="resetUlti" type="button" class="btn btn-primary mt-4" style="max-width:100px">Reset</button>
                                </div>
                                
                                <div class="d-flex justify-content-center mt-3" style="width:300px">
                                    <button v-if="!ultiUrl" @click="this.$refs.ultiFile.click()" type="button" class="btn btn-primary">Change</button>
                                    <input @change="setUltiImage" class="form-control" ref="ultiFile" type="file" id="formFile" style="display:none">
                                </div>
                            </div>
                        </div>
                        <div v-if="!updatingUrl" class="row mb-3">
                            <label for="formFile" class="col-sm-2 col-form-label" >Category Image <b v-if="v$.image.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                                <input @change="setImage" class="form-control" :class="[v$.image.$error ? 'is-invalid' : '']" ref="file" type="file" id="formFile">
                            </div>
                        </div>
                        <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" for="basic-default-phone"> Parent Category <b style="cursor:pointer;color:blue;" @click="resetCategory" v-if="selected.category_id">reset</b></label>
                            <div class="col-sm-10">
                                <input v-model="form.category" v-debounce:500ms="searchCategory" :readonly="selected.category_id != ''" type="text" class="form-control" id="basic-default-name" placeholder="John Doe" />
                                <span v-if="foundCategory" @click="chooseCategory">{{foundCategory.name}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name - AZ<b v-if="selected.id && !v$.az_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.az_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.az_name" type="text" class="form-control" :class="[v$.az_name.$error ? 'is-invalid' : '',categoryerror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='categoryerror' style="color:rgb(255,62,29)">{{categoryerror}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name - EN<b v-if="selected.id && !v$.en_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.en_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.en_name" type="text" class="form-control" :class="[v$.en_name.$error ? 'is-invalid' : '',categoryerror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='categoryerror' style="color:rgb(255,62,29)">{{categoryerror}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name - RU<b v-if="selected.id && !v$.ru_name.$error" style="color:yellow;"> - updating</b><b v-if="v$.ru_name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.ru_name" type="text" class="form-control" :class="[v$.ru_name.$error ? 'is-invalid' : '',categoryerror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='categoryerror' style="color:rgb(255,62,29)">{{categoryerror}}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">İnformation</label>
                            <div class="col-sm-10">
                            <div class="row">
                                <div class="col">
                                    <input v-model="selected.title" class="form-control" type="text" style="display:inline-block" id="basic-default-name" placeholder="John Doe" />
                                </div>
                                <div class="col">
                                    <button v-if="!upInf.id" @click="addInf(1)" type="button" class="btn btn-primary ms-2">Add AZ</button>
                                    <button v-if="!upInf.id" @click="addInf(2)" type="button" class="btn btn-primary ms-2">Add EN</button>
                                    <button v-if="!upInf.id" @click="addInf(3)" type="button" class="btn btn-primary ms-2">Add RU</button>
                                    <button v-if="upInf.id" @click="addInf(4)" type="button" class="btn btn-primary ms-2">Update</button>
                                    <button v-if="upInf.id" @click="[upInf.id = null],[upInf.lang = null]" type="button" class="btn btn-primary ms-2">X</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row mb-3" style="display: flex;flex-wrap: wrap;justify-content: flex-start">
                            <div class="col">
                                <div v-if="selected.az_inf" v-for="inf in selected.az_inf" :key="inf.title">
                                    <label>{{inf.title}}</label>
                                    <i v-if="selected.en_inf.some(item => item.id === inf.id) && selected.ru_inf.some(item => item.id === inf.id)" @click="deleteInf(inf.id)" class="bx bxs-trash" style="cursor:pointer;margin-left:5px;"></i>
                                    <i @click="[upInf.id = inf.id],[upInf.lang = 0],[selected.title = inf.title]" class="bx bx-edit" style="cursor:pointer;margin-left:5px;"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div v-if="selected.en_inf" v-for="inf in selected.en_inf" :key="inf.title">
                                    <label>{{inf.title}}</label>
                                    <i v-if="selected.az_inf.some(item => item.id === inf.id) && selected.ru_inf.some(item => item.id === inf.id)" @click="deleteInf(inf.id)" class="bx bxs-trash" style="cursor:pointer;margin-left:5px;"></i>
                                    <i @click="[upInf.id = inf.id],[upInf.lang = 1],[selected.title = inf.title]" class="bx bx-edit" style="cursor:pointer;margin-left:5px;"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div v-if="selected.ru_inf" v-for="inf in selected.ru_inf" :key="inf.title">
                                    <label>{{inf.title}}</label>
                                    <i v-if="selected.az_inf.some(item => item.id === inf.id) && selected.en_inf.some(item => item.id === inf.id)" @click="deleteInf(inf.id)" class="bx bxs-trash" style="cursor:pointer;margin-left:5px;"></i>
                                    <i @click="[upInf.id = inf.id],[upInf.lang = 2],[selected.title = inf.title]" class="bx bx-edit" style="cursor:pointer;margin-left:5px;"></i>
                                </div>
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
                                        <input v-model="page.search" v-debounce:400ms="searchCategories" type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
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
                        <th>Parent </th>
                        <th>Product count</th>
                        <th>Date</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="category in categories" :key="category.id">
                            <td class="dt-checkboxes-cell">
                                <input type="checkbox" class="dt-checkboxes form-check-input">
                            </td>
                            <td style="overflow:hidden;">
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            <img :src="'http://127.0.0.1:8000/api/images/categories/'+category.image" alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column" style="overflow:hidden;width:150px;">
                                        <span class="emp_name text-truncate">{{category.name}}</span>
                                    </div>
                                </div>
                            </td>
                            <td v-if="category.parent">{{category.parent.name}}</td>
                            <td v-else>NULL</td>
                            <td>{{category.prcount}}</td>
                            <td>{{category.date}}</td>
                            <td>
                                <a @click.prevent="deletE(category.id)" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-trash"></i></a>
                                <a @click.prevent="editE(category)" href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="bx bxs-edit"></i></a>

                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <div class="row" style="padding-left:40px;padding-top:20px;">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {{page.page}} to {{page.perPage}} of {{categoryCount}} categories</div>
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
            <!-- Modal to add new record -->

    </div>
    </template>


    <script setup>
    import { ref,reactive,watch,onMounted } from 'vue'
    import store from '../../store'
    import axios from 'axios'
    import { useVuelidate } from '@vuelidate/core'
    import { required } from '@vuelidate/validators'
    const categoryerror = ref('')
    const categories = ref([])
    const foundCategory = ref('')
    const previewUrl = ref('')
    const pagesCount = ref('')
    const categoryCount = ref('')
    const selectedLanguage = ref(1)
    const loading = ref(false)
    const updatingUrl = ref(false)
    const ultiUrl = ref(false)
    const ultiImg = ref(false)
    const titleAz = ref(1)
    const titleEn = ref(1)
    const titleRu = ref(1)
    const incomplete = ref(false)
    const file = ref(null)
    const ultiFile = ref(null)
    const form = reactive({
        category:'',
        parent:true
    })
    const upInf = reactive({
        id:null,
        lang:null
    })
    const page = reactive({
        page:1,
        search:'',
        perPage:'10',
        order:false,
        orderBy:true
    })
    const currentPage = parseInt(page.page)
    const selected = reactive({
        az_name:'',
        en_name:'',
        ru_name:'',
        id:'',
        image:null,
        category_id:'',
        az_inf:[],
        en_inf:[],
        ru_inf:[],
        deletedInfs:[],
        title:''
    })
    const rules = {
        az_name:{required},
        en_name:{required},
        ru_name:{required},
        image:{required},
    }
    watch(()=>selected.image,()=>{
        if(!selected.image){
            previewUrl.value = false
        }
    })
    watch(()=>selected.az_name,()=>{
        if(categoryerror){
            categoryerror.value = ''
        }
    })
    watch(()=>selected.en_name,()=>{
        if(categoryerror){
            categoryerror.value = ''
        }
    })
    watch(()=>selected.ru_name,()=>{
        if(categoryerror){
            categoryerror.value = ''
        }
    })
    watch(()=>page.perPage,()=>{
        page.page = 1
        loadCategories()
    })

    const v$ = useVuelidate(rules,selected)
    const setImage = (event) => {
        selected.image = event.target.files[0]
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(selected.image);
    }
    const setUltiImage = (event) => {
        ultiImg.value = event.target.files[0]
        const reader = new FileReader();
        reader.onload = (e) => {
            ultiUrl.value = e.target.result;
        };
        reader.readAsDataURL(ultiImg.value);
    }
    const changeImage = async() => {
        const formData = new FormData()
        formData.append("image",ultiImg.value)
        formData.append("category_id",selected.id)
        loading.value = true
        await axios.post('http://127.0.0.1:8000/api/changecategoryimage',formData,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                loading.value = false
                resetUlti()
                updatingUrl.value = response.data.category.image
                loadCategories()
            }).catch(err=>{
                loading.value = false
            })
    }
    const makeCategory = () => {
        if(!selected.id){
            v$.value.$validate()
        }else{
            v$.value.az_name.$touch()
            v$.value.en_name.$touch()
            v$.value.ru_name.$touch()
        }
        if(v$.value.$error){
            return
        }
        incomplete.value = false
        selected.az_inf.forEach(element => {
            if(!selected.en_inf.some(item => item.id === element.id) || !selected.ru_inf.some(item => item.id === element.id)){
                alert('information isnt completed')
                incomplete.value = true
            }else{
                incomplete.value = false
            }
        })
        if(incomplete.value){
            return
        }
        selected.en_inf.forEach(element => {
            if(!selected.az_inf.some(item => item.id === element.id) || !selected.ru_inf.some(item => item.id === element.id)){
                alert('information isnt completed')
                incomplete.value = true
            }else{
                incomplete.value = false
            }
        })
        if(incomplete.value){
            return
        }
        selected.ru_inf.forEach(element => {
            if(!selected.az_inf.some(item => item.id === element.id) || !selected.en_inf.some(item => item.id === element.id)){
                alert('information isnt completed')
                incomplete.value = true
            }else{
                incomplete.value = false
            }
        })
        if(selected.az_inf.length === 0){
            if(window.confirm('Are you sure to create a category without any information? (You cant use that category to add product)')){
                incomplete.value = false
            }else{
                incomplete.value = true
            }
        }
        if(incomplete.value){
            return
        }
        const formData = new FormData()
        formData.append("image",selected.image)
        formData.append("az_name",selected.az_name)
        formData.append("en_name",selected.en_name)
        formData.append("ru_name",selected.ru_name)
        formData.append("id",selected.id)
        formData.append("category_id",selected.category_id)
        const azJson = JSON.stringify(selected.az_inf)
        formData.append('az_inf',azJson)
        const enJson = JSON.stringify(selected.en_inf)
        formData.append('en_inf',enJson)
        const ruJson = JSON.stringify(selected.ru_inf)
        formData.append('ru_inf',ruJson)
        const deletedIds = JSON.stringify(selected.deletedInfs)
        formData.append('deleted',deletedIds)
        loading.value = true
        store.dispatch('addcategory',formData).then(async()=>{
            if(selected.id){
                editE(store.state.category)
            }
            page.order = false
            loading.value = false
            await loadCategories()
        }).catch((error)=>{
            if(error.response){
                categoryerror.value = error.response.data.message
            }
            loading.value = false
        })
    }
    const addInf = (n) => {
        if(n === 1){
            if(selected.az_inf.some(item => item.title === selected.title)){
                alert('title already exists')
                return
            }
            selected.az_inf.push({id:'a'+titleAz.value,title:selected.title,base:false})
            titleAz.value++
        }else if(n === 2){ 
            if(selected.en_inf.some(item => item.title === selected.title)){
                alert('title already exists')
                return
            }
            selected.en_inf.push({id:'a'+titleEn.value,title:selected.title,base:false})
            titleEn.value++
        }else if(n === 3){
            if(selected.ru_inf.some(item => item.title === selected.title)){
                alert('title already exists')
                return
            }
            selected.ru_inf.push({id:'a'+titleRu.value,title:selected.title,base:false})
            titleRu.value++
        }else if(n === 4){
            if(upInf.lang === 0){
                if(selected.az_inf.some(item => item.title === selected.title)){
                    alert('title already exists')
                    return
                }
                selected.az_inf[selected.az_inf.findIndex(item => item.id === upInf.id)].title = selected.title
            }else if(upInf.lang === 1){
                if(selected.en_inf.some(item => item.title === selected.title)){
                    alert('title already exists')
                    return
                }
                selected.en_inf[selected.en_inf.findIndex(item => item.id === upInf.id)].title = selected.title
            }else if(upInf.lang === 2){
                if(selected.ru_inf.some(item => item.title === selected.title)){
                    alert('title already exists')
                    return
                }
                selected.ru_inf[selected.ru_inf.findIndex(item => item.id === upInf.id)].title = selected.title
            }
            upInf.id = null
            upInf.lang = null
        }
    }
    const deleteInf = (id) => {
        const indexToRemove1 = selected.az_inf.findIndex(item => item.id === id);
        if(selected.az_inf[indexToRemove1].base){
            selected.deletedInfs.push({id:id})
        }
        if (indexToRemove1 !== -1) {
            selected.az_inf.splice(indexToRemove1, 1);
        }
        const indexToRemove2 = selected.en_inf.findIndex(item => item.id === id);
        if (indexToRemove2 !== -1) {
            selected.en_inf.splice(indexToRemove2, 1);
        }
        const indexToRemove3 = selected.ru_inf.findIndex(item => item.id === id);
        if (indexToRemove3 !== -1) {
            selected.ru_inf.splice(indexToRemove3, 1);
        }
    }
    const reset = () => {
        selected.az_name = ''
        selected.en_name = ''
        selected.ru_name = ''
        selected.az_inf = []
        selected.en_inf = []
        selected.ru_inf = []
        selected.title = ''
        selected.deletedInfs = []
        console.log(selected.deletedInfs)
        selected.id = ''
        form.category = ''
        selected.category_id = ''
        if(file.value){
            file.value.value = null
        }
        updatingUrl.value = false
        v$.value.$reset()
    }
    const resetUlti = () => {
        ultiImg.value = false
        ultiUrl.value = false
        if(ultiFile.value){
            ultiFile.value.value = ''
        }
    }
    const searchCategory = async() => {
    if(form.category){
            await axios.post('http://127.0.0.1:8000/api/categorys',form,{
                headers: {
                    Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                }
            }).then((response)=>{
                foundCategory.value = response.data.category
            })
        }else{
            foundCategory.value = ''
        }
    }
    const chooseCategory = () => {
        if(foundCategory.value){
            selected.category_id = foundCategory.value.id
            form.category = foundCategory.value.name
            foundCategory.value = ''
        }
    }
    const resetCategory = () => {
        selected.category_id = ''
        form.category = ''
    }
    onMounted(async()=>{
        await loadCategories()
    })

    const deletE = async(id) => {
        if(window.confirm('Are you sure you want to delete?')){
            await axios.post('http://127.0.0.1:8000/api/deletecategory/'+id,null,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    loadCategories()
                    alert(response.data.message)
                })
        }
    }
    const editE = (category) => {
        reset()
        if(category.parent){
            form.category = category.parent.name
            selected.category_id = category.parent.id
        }
        if(category.informations.length > 0){
            category.informations.forEach(element => {
                selected.az_inf.push({id:element.id,title:element.translations[0].title,base:true})
                selected.en_inf.push({id:element.id,title:element.translations[1].title,base:true})
                selected.ru_inf.push({id:element.id,title:element.translations[2].title,base:true})
            })
        }
        selected.id = category.id
        selected.az_name = category.translations[0].name
        selected.en_name = category.translations[1].name
        selected.ru_name = category.translations[2].name
        updatingUrl.value = category.image
        window.location.href = '#'
    }
    const searchCategories = () => {
        page.page = 1
        loadCategories()
    }
    const order = () => {
        page.order = true
        loadCategories()
        page.orderBy = !page.orderBy
    }
    const loadCategories = async() => {
        await axios.post('http://127.0.0.1:8000/api/loadcategories',page,{
                    headers: {
                        Authorization: 'Bearer '+localStorage.getItem('TOKEN')
                    }
                }).then((response)=>{
                    categories.value = response.data.categories.data
                    pagesCount.value = response.data.last
                    categoryCount.value = response.data.count
                })
    }
    const changePage = (n) => {
        page.page = n
        loadCategories()
    }
    const previousPage = () => {
        if(page.page != 1){
            page.page--
            loadCategories()
        }
    }
    const nextPage = () => {
        if(page.page != pagesCount){
            page.page++
            loadCategories()
        }
    }
    </script>

    <style scoped>
    .activePager{
        background-color:rgb(105,108,255);
        color:white;
    }
    .deactivePager{
        background-color:rgb(240,242,244);color:rgb(105,122,141);
    }
    </style>
