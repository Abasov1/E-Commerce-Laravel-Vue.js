<template>
    <div class="col d-none d-xl-block">
        <form @submit.prevent="searcho" class="js-focus-state">
            <label class="sr-only" for="searchproduct">Search</label>
            <div class="input-group">
                <input ref="searchBar" style="z-index: 5000;" autocomplete="off" v-model="data.index" v-debounce:400ms="search" @focus="focus = true" @blur="lezgo" type="text" class="form-control py-2 pl-5 font-size-15 border-right-0 height-40 border-width-2 rounded-left-pill border-primary" name="email" id="searchproduct-item" :placeholder="store.state.user.language.search_bar.placeholder" aria-label="Search for Products" aria-describedby="searchProduct1" required>
                <div class="input-group-append" style="z-index: 5000;">
                    <button class="btn btn-primary height-40 py-2 px-3 rounded-right-pill" type="submit" id="searchProduct1">
                        <span class="bi bi-search font-size-16"></span>
                    </button>
                </div>
                <div v-if="results && focus" class="onnan">
                    <div v-if="results[1].data.length" class="bunnan" v-for="br in results[1].data" :key="br.id" @click="go(1,br.slug)"><router-link :to="{name:'Brand',params:{slug:br.slug}}" style="text-decoration:none;color:rgb(51,62,72)">{{br.name}} - <b>{{store.state.user.language.search_bar.brand}}</b></router-link></div>
                    <div v-if="results[2].data.length" class="bunnan" v-for="br in results[2].data" :key="br.id" @click="go(2,br.slug)"><router-link :to="{name:'Merchant',params:{slug:br.slug}}" style="text-decoration:none;color:rgb(51,62,72)">{{br.name}} - <b>{{store.state.user.language.search_bar.merchant}}</b></router-link></div>
                    <div v-if="results[0].data.length" class="bunnan" v-for="br in results[0].data" :key="br.id" @click="go(0,br.slug)">
                    <router-link :to="{name:'Product',params:{slug:br.slug}}" style="color:rgb(51,62,72)">
                        <img style="max-width:80px;display:inline-block" :src="'http://127.0.0.1:8000/api/images/products/'+br.images[0].image">
                        {{br.name}}
                    </router-link>
                    </div>
                    <div v-if="!results[0].data.length && !results[1].data.length && !results[2].data.length" style="padding:5px 0px;">
                        {{store.state.user.language.search_bar.no_result}}
                    </div>
                </div>
            </div>
        </form>
        <div v-if="results && focus" class="overlay"></div>
    </div>
</template>
<script setup>
import {ref,onMounted,reactive,watch} from 'vue'
import store from '../store'
import { useRouter } from 'vue-router'
const router = useRouter()
const focus = ref(false)
const brands = ref(false)
const results = ref(false)
const merchants = ref(false)
const products = ref(false)
const searchBar = ref(false)
const data = reactive({
    index:''
})
const search = () => {
    if(data.index.length != ''){
        store.dispatch('search',data).then(()=>{
            results.value = store.state.results
            setResult()
        })
    }else{
        results.value = false 
    }
}
watch(()=>store.state.user.language,()=>{
    if(results.value){
        setResult()
    }
})
const setResult = () => {
    if (localStorage.getItem('lang') === 'az'){
        results.value[0].data.forEach(item => {
            item.name = item.translations[0].name
        });
    }else if (localStorage.getItem('lang') === 'en'){
        results.value[0].data.forEach(item => {
            item.name = item.translations[1].name
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        results.value[0].data.forEach(item => {
            item.name = item.translations[2].name
        });
    }
}
const searcho = () => {
    if(data.index != ''){
        store.state.results = false
        searchBar.value.blur()
        router.push({name:'Search',params:{index:data.index}})
    }
}
const lezgo = () => {
    setTimeout(() => {
        focus.value = false
    }, 200);
}
const go = (n,slug) => {
    if(n === 0){
        router.push({name:'Product',params:{slug:slug}})
    }else if(n === 1){
        router.push({name:'Brand',params:{slug:slug}})
    }else if(n === 2){
        router.push({name:'Merchant',params:{slug:slug}})
    }
}
</script>
<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
  filter: blur(5px); /* Apply a blur effect */
  z-index: 4999; /* Make sure the overlay is on top of other elements */
}
.onnan{
    width:inherit;
    background-color:white;
    position:absolute;
    top:50px;
    overflow:scroll;
    max-height:500px;
    padding-left:30px;
    padding-top:10px;
    padding-bottom:5px;
    z-index:5000;
}
.bunnan{
    cursor:pointer;
    padding:5px 0px;
    font-size:12px;
}
.bunnan:hover{
    background-color:rgb(233, 237, 201);
}
/* .onnan{
    overflow-y:hidden;
} */
.onnan::-webkit-scrollbar {
  width: 8px;
}

.onnan::-webkit-scrollbar-track {
  background-color: #f5f5f5;
}

.onnan::-webkit-scrollbar-thumb {
  background-color: #ccc;
  border-radius: 10px;
}

.onnan::-webkit-scrollbar-thumb:hover {
  background-color: #999;
}
</style>