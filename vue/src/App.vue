<template>
    <router-view>

    </router-view>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router'
import store from './store'
import { onMounted } from 'vue'
onMounted(()=>{
    store.dispatch('loadUser')
})
</script>

<style scoped>

</style>
