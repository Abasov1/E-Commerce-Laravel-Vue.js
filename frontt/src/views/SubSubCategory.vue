<template>
    <main v-if="show" id="content" role="main">
        <Filter v-if="show" :catid="category[0].parent.id" />
    </main>
</template>
<script setup>
import store from '../store'
import { onMounted,ref,reactive,computed } from 'vue'
import Filter from '../components/Filter.vue'

const show = ref(false)
const props = defineProps({
    slug: String,
    parent:String
})   
onMounted(()=>{
    window.scrollTo(0,0);
    store.dispatch('loadcategory',props.slug).then(()=>{
        show.value = true
    })
})
const category = computed(()=>store.state.category)

</script>