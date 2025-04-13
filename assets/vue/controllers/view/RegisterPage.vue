<template>
    <div class="bg-stone-50 dark:bg-stone-900 my-20 border border-stone-200 dark:border-stone-700 max-w-lg rounded-xl mx-auto p-5">
        <h1 class="text-2xl text-stone-950 dark:text-stone-50">Inscription</h1>
        <div class="flex my-3">
            <span class="text-stone-300 dark:text-stone-700 me-1">Vous avez déjà un compte ?</span>
            <a class="text-stone-950 dark:text-stone-50 hover:underline" :href="url.login">Se connecter</a>
        </div>

        <form @submit.prevent="handleSubmit">
            <Input 
                id="input_lastname"
                class="mb-3"
                name="lastname"
                label="Nom"
                type="text"
                v-model="formData.lastname"
                :is-error="errors.lastname.isError"
                :error-message="errors.lastname.message"
                :required="true"
            />

            <Input 
                id="input_firstname"
                class="mb-3"
                name="firstname"
                label="Prénom"
                type="text"
                v-model="formData.firstname"
                :is-error="errors.firstname.isError"
                :error-message="errors.firstname.message"
                :required="true"
            />

            <Input 
                id="input_email"
                class="mb-3"
                name="email"
                label="Email"
                type="email"
                v-model="formData.email"
                :is-error="errors.email.isError"
                :error-message="errors.email.message"
                :required="true"
            />

            <Input 
                id="input_password_first"
                class="mb-3"
                name="password[first]"
                label="Mot de passe"
                type="password"
                v-model="formData.password.first"
                :is-error="errors.password.isError"
                :required="true"
            />

            <Input 
                id="input_password_second"
                class="mb-3"
                name="password[second]"
                label="Confirmer le mot de passe"
                type="password"
                v-model="formData.password.second"
                :is-error="errors.password.isError"
                :error-message="errors.password.message"
                :required="true"
            />

            <Button 
                class="w-full mt-5"
                color="gradient"
                type="submit"
                :loading="loading"
                :disabled="loading"
            >
                S'inscrire
            </Button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Button from '../../components/button/Button.vue';
import Input from '../../components/input/Input.vue';

import api from '../../../js/api.js';

const props = defineProps({
    url: {
        type: Object,
        default: {
            login: '/',
        }
    },
    api: {
        type: Object,
        default: {
            register: '/',
        }
    }
});

const loading = ref(false);

const formData = ref({
    lastname: '',
    firstname: '',
    email: '',
    password: {
        first: '',
        second: ''
    }
});

const errors = ref({
    lastname: {
        isError: false,
        message: ''
    },
    firstname: {
        isError: false,
        message: ''
    },
    email: {
        isError: false,
        message: ''
    },
    password: {
        isError: false,
        message: ''
    }
});

const handleSubmit = async () => {
    try {
        loading.value = true;
        const response = await api.post(props.api.register, formData.value);

        if (response.success) {
            window.location.href = props.url.login;
        } else {
            loading.value = false;
            for (const [key, messages] of Object.entries(response.errors)) {
                if (errors.value[key]) {
                    errors.value[key].isError = true;
                    errors.value[key].message = messages[0];
                }
            }
        }

    } catch (errorCatch) {
        console.error(errorCatch);
    }
};
</script>