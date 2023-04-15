<template>
    <div>
        Loading...
    </div>
</template>


<script setup>
import store from '../store'
import {onMounted} from 'vue'
onMounted(()=>{
    store.dispatch('loadUser')
})
</script>

<style scoped>
div{
    position:fixed;
    height:100%;
    width:100%;
    display:flex;
    align-items:center;
    justify-content:center;
}
</style>