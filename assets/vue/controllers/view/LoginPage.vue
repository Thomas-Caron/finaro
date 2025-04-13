<template>
    <div class="bg-stone-50 dark:bg-stone-900 my-20 border border-stone-200 dark:border-stone-700 max-w-lg rounded-xl mx-auto p-5">
        <h1 class="text-2xl text-stone-950 dark:text-stone-50">Connexion</h1>
        <div class="flex my-3">
            <span class="text-stone-300 dark:text-stone-600 me-1">Vous n'avez pas encore de compte ?</span>
            <a class="text-stone-950 dark:text-stone-50 hover:underline" :href="url.register">
                S'inscrire
            </a>
        </div>

        <form method="post">
            <Input 
                id="input_email"
                class="mb-3"
                name="email"
                label="Adresse email"
                type="email"
                v-model="form.email"
                :is-error="error.isError"
                :required="true"
                :autofocus="true"
            />

            <Input 
                id="input_password"
                class="mb-3"
                name="password"
                label="Mot de passe"
                type="password"
                v-model="form.password"
                :is-error="error.isError"
                :required="true"
            />

            <Input
                id="input_token"
                name="tokenCsrf"
                type="hidden"
                v-model="form.token"
            />
            

            <Alert 
                v-if="error.isError"
                type="error"
                class="mt-3"
            >
                {{ error.message }}
            </Alert>

            <Button class="w-full my-5" type="submit" color="gradient">Se connecter</Button>
        </form>

        <a class="block text-center text-stone-950 dark:text-stone-50 hover:underline" :href="url.forgetPassword">
            Mot de passe oublié ?
        </a>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import Alert from '../../components/alert/Alert.vue';
import Button from '../../components/button/Button.vue';
import Input from '../../components/input/Input.vue';

const props = defineProps({
    lastUsername: {
        type: String,
        default: ''
    },
    tokenCsrf: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    },
    url: {
        type: Object,
        default: () => ({
            'home': '/',
            'login': '/',
            'register': '/',
            'forgetPassword': '/'
        })
    }
});

const form = ref({
    email: props.lastUsername || '',
    password: '',
    token: props.tokenCsrf || ''
});

const error = ref({
    isError: props.error ? true : false,
    message: props.error || ''
});
</script>