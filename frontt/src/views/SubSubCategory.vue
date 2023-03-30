<template>
    <main v-if="show" id="content" role="main">
        {{categories[0].name}}
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed } from 'vue'
const show = ref(false)
const props = defineProps({
    slug: String,
    parent:String
})   
onMounted(()=>{
    store.dispatch('loadcategory',props.slug).then(()=>{
        show.value = true
    })
})
const categories = computed(()=>store.state.category)

</script>