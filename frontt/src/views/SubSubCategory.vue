<template>
    <main v-if="category" id="content" role="main">
        <Filter v-if="category" :catid="category[0].parent.id" />
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed,watch } from 'vue'
import Filter from '../components/Filter.vue'
const category = ref(false)
const show = ref(false)
const props = defineProps({
    slug: String,
    parent:String
})
onMounted(()=>{
    window.scrollTo(0,0);
    store.dispatch('loadcategory',props.slug).then(()=>{
        category.value = store.state.category
        setCategories()
    })
})
watch(()=>store.state.user.language,()=>{
    if(category.value){
        setCategories()
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
