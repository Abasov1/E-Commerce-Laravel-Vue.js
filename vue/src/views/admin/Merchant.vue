<template>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Horizontal Layouts</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Merchant input</h5> <small class="text-muted float-end">netersen gulenver</small>
                </div>
                <div class="card-body">
                    <form @submit.prevent="makeMerchant">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name <b v-if="v$.name.$error" style="color:rgb(255,62,29)"> - required</b></label>
                            <div class="col-sm-10">
                            <input v-model="selected.name" type="text" class="form-control" :class="[v$.name.$error ? 'is-invalid' : '',merchanterror ? 'is-invalid' : '']" id="basic-default-name" placeholder="John Doe" />
                            <span v-if='merchanterror' style="color:rgb(255,62,29)">{{merchanterror}}</span>
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
    </div>
    </template>


    <script setup>
    import { ref,reactive,watch } from 'vue'
    import store from '../../store'
    import axios from 'axios'
    import { useVuelidate } from '@vuelidate/core'
    import { required } from '@vuelidate/validators'
    const merchanterror = ref('')
    const selected = reactive({
        name:'',
    })
    const rules = {
        name:{required},
    }

    watch(()=>selected.name,()=>{
        if(merchanterror){
            merchanterror.value = ''
        }
    })

    const v$ = useVuelidate(rules,selected)

    const makeMerchant = () => {
        v$.value.$validate()
        if(v$.value.$error){
            return
        }
        store.dispatch('addmerchant',selected).catch(err=>{
            merchanterror.value = err.response.data.message
        })
    }

    const reset = () => {
        selected.name = ''
        v$.value.$reset()
    }

    </script>

    <style scoped>

    </style>
