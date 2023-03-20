
<template>
    <div>
        <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Login</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <router-link :to="{name:'Sagol'}" href="#" class="font-medium text-indigo-600 hover:text-indigo-500">register</router-link>
        </p>
        </div>
        <form class="mt-8 space-y-6" @submit.prevent="login">
        <input type="hidden" name="remember" value="true">
        <div class="-space-y-px rounded-md shadow-sm">
            <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input id="email-address" v-model="user.email" name="email" type="email" autocomplete="email" required class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Email address">
            <div v-if="v$.email.$error" style="font-size:12px;color:red;">{{ v$.email.$errors[0].$message }}</div>
            <div v-if="responseMsg != false" style="font-size:12px;color:red;">{{ responseMsg }}</div>
            </div>
            <div>
            <label for="password" class="sr-only">Password</label>
            <input id="password" v-model="user.password" name="password" type="password" autocomplete="current-password" required class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Password">
            <div v-if="v$.password.$error" style="font-size:12px;color:red;">{{ v$.password.$errors[0].$message }}</div>
            <div v-if="responseMsg2 != false" style="font-size:12px;color:red;">{{ responseMsg2 }}</div>

            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
            <input v-model="user.remember" id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
            </div>

        </div>

        <div>
            <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                </svg>
            </span>
            Sign in
            </button>
        </div>
        </form>
</template>


<script setup>
    import { ref,watch,reactive } from 'vue'
    import { useStore } from 'vuex'
    import { useRouter } from 'vue-router'
    import { useVuelidate } from '@vuelidate/core'
    import { required,helpers , email, minLength, maxLength } from '@vuelidate/validators'
    const store = useStore()
    const router = useRouter()
    const responseMsg = ref(false);
    const responseMsg2 = ref(false);
    const msg = ref('');
    const user = reactive({
        email:'',
        password:'',
        remember:false
    })
    watch(() => user.email,() => {
        v$.value.email.$touch()
        responseMsg.value = false
    })
    watch(() => user.password,() => {
        v$.value.password.$touch()
        responseMsg2.value = false
    })

    const rules = {
        email: { required, email },
        password: { required, minLength: minLength(5), maxLength: maxLength(20) },
        remember: { required }
    }

    const v$ = useVuelidate(rules,user);

    const login = () => {
        responseMsg.value = false
        v$.value.$validate()
        if(v$.value.$error){
            return
        }
        store.dispatch('login',user).then(() => {
            router.push({name:'Dashboard'})
        }).catch(err=>{
            if(err.response.data.message){
                responseMsg.value = err.response.data.message
            }else{
                responseMsg2.value = err.response.data.error
            }
        })
    }
</script>

<style scoped>

</style>
