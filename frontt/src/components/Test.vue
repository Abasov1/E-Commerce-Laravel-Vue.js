<template>
  <div v-if="!store.state.loading">
    <div v-if="store.state.user.isLoggedIn">Logged In</div>
    <div>{{store.state.user.language.welcome}}</div>
    <button @click="changeLung('az')">az</button>
    <button @click="changeLung('en')">en</button>
    <button @click="changeLung('ru')">ru</button>
  </div>
</template>

<script setup>
import { ref,onMounted } from 'vue'
import store from '../store'
import az from '../lang/az.json'
import en from '../lang/en.json'
const changeLung = (lang) =>{
    store.commit('changeLang',lang)
}
</script>