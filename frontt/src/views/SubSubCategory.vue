<template>
    <main id="content" role="main">
        <div v-if="category">
            <Filter :catid="category[0].parent.id" />
        </div>
        <div v-if="noResult" style="display:flex;width:100%;justify-content:center;align-items:center;padding:30px 0px;">
            <h1>{{store.state.user.language.search_bar.no_result}}</h1>
        </div>
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
import Filter from '../components/Filter.vue'
const category = ref(false)
const noResult = ref(false)
const show = ref(false)
const props = defineProps({
    slug: String,
    parent:String
})
onMounted(()=>{
    window.scrollTo(0,0);
    store.dispatch('loadcategory',props.slug).then(()=>{
        category.value = store.state.category
        if(!category.value){
            document.title = store.state.user.language.search_bar.no_result
            noResult.value = true
            return
        }
        setCategories()
    })
})
watch(()=>store.state.user.language,()=>{
    if(category.value){
        setCategories()
    }
    if(!category.value && noResult){
        document.title = store.state.user.language.search_bar.no_result
    }
})
const setCategories = () => {
    if (localStorage.getItem('lang') === 'az'){
        category.value[0].parent.name = category.value[0].parent.translations[0].name
        document.title = category.value[0].parent.name
    }else if (localStorage.getItem('lang') === 'en'){
        category.value[0].parent.name = category.value[0].parent.translations[1].name
        document.title = category.value[0].parent.name
    }else if (localStorage.getItem('lang') === 'ru'){
        category.value[0].parent.name = category.value[0].parent.translations[2].name
        document.title = category.value[0].parent.name
    }
}

</script>
