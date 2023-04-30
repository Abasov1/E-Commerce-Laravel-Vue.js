<template>
    <main id="content" role="main">
        <div v-if="categories" class="mb-6 bg-gray-7 py-6">
            <div class="container">
                <router-link :to="{name:'Categories'}" class="my-link text-black">{{store.state.user.language.categories}} > </router-link>{{categories[0].parent.name}}
                <div class=" d-flex justify-content-between border-bottom border-color-1 flex-lg-nowrap flex-wrap border-md-down-top-0 border-md-down-bottom-0 mb-5">
                    <h3 class="section-title mb-0 pb-2 font-size-22">{{categories[0].parent.name}}</h3>
                </div>
                <div class="row flex-nowrap flex-md-wrap overflow-auto overflow-md-visble">
                    <div v-if="categories"  v-for="cat in categories" :key="cat.id" class="col-md-4 col-lg-3 col-xl-4 col-xl-2gdot4 mb-3 flex-shrink-0 flex-md-shrink-1">
                        <div class="bg-white overflow-hidden shadow-on-hover h-100 d-flex align-items-center">
                            <router-link :to="{name:'SubCategory',params:{parent:cat.parent.slug,slug:cat.slug}}" class="d-block pr-2 pr-wd-6">
                                <div class="media align-items-center">
                                    <div class="pt-2">
                                        <img class="img-fluid transform-rotate-15" :src="'http://127.0.0.1:8000/api/images/categories/'+cat.image" alt="Image Description">
                                    </div>
                                    <div class="ml-3 media-body">
                                        <h6 class="mb-0 text-gray-90">{{cat.name}}</h6>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="noResult" style="display:flex;width:100%;justify-content:center;align-items:center;padding:30px 0px;">
            <h1>{{store.state.user.language.search_bar.no_result}}</h1>
        </div>
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
const categories = ref(false)
const noResult = ref(false)
const props = defineProps({
    slug: String,
})
onMounted(()=>{
    window.scrollTo(0,0);
    document.title = store.state.user.language.loading
    store.dispatch('loadcategory',props.slug).then(()=>{
        categories.value = store.state.category
        if(!categories.value){
            noResult.value = true
            document.title = store.state.user.language.search_bar.no_result
            return
        }
        setCategories()
    })
})
watch(()=>store.state.user.language,()=>{
    if(categories.value){
        setCategories()
    }
    if(!categories.value && noResult){
        document.title = store.state.user.language.search_bar.no_result
    }
})
const setCategories = () => {
    if (localStorage.getItem('lang') === 'az'){
        categories.value[0].parent.name = categories.value[0].parent.translations[0].name
        document.title = categories.value[0].parent.name
        categories.value.forEach(item => {
            item.name = item.translations[0].name
        });
    }else if (localStorage.getItem('lang') === 'en'){
        categories.value[0].parent.name = categories.value[0].parent.translations[1].name
        document.title = categories.value[0].parent.name
        categories.value.forEach(item => {
            item.name = item.translations[1].name
        });
    }else if (localStorage.getItem('lang') === 'ru'){
        categories.value[0].parent.name = categories.value[0].parent.translations[2].name
        document.title = categories.value[0].parent.name
        categories.value.forEach(item => {
            item.name = item.translations[2].name
        });
    }
}

</script>
