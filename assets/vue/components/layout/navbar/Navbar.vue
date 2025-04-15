<template>
    <nav class="container bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-700 rounded-full mx-auto mt-4">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-7 py-4">
            <a :href="url.home" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold text-stone-900 whitespace-nowrap dark:text-stone-50">Finaro</span>
            </a>

            <NavbarUser v-if="isConnected" :user="user" :url="url" />

            <div v-else>
                <button type="button" class="w-10 h-10 inline-flex items-center justify-center rounded-lg md:hidden text-stone-900 dark:text-stone-50 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-stone-200 dark:hover:bg-stone-700 dark:focus:ring-stone-600" data-collapse-toggle="dropdown-menu" aria-controls="dropdown-menu" aria-expanded="false">
                    <span class="sr-only">Open menu</span>
                    <Icon class="!size-6" name="Menu" />
                </button>
                <div class="hidden w-full md:block md:w-auto">
                    <ul class="font-medium flex flex-row items-center space-x-8 md:border-0">
                        <li>
                            <ButtonNavbarMenu :url="url.register">
                                S'inscrire
                            </ButtonNavbarMenu>
                        </li>
                        <li>
                            <ButtonLink color="gradient" :url="url.login">
                                Se connecter
                            </ButtonLink>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div id="dropdown-menu" class="hidden container mx-auto mt-1 rounded-lg bg-stone-50 border border-stone-200 dark:border-stone-700 dark:bg-stone-900">
        <ul class="font-medium flex flex-col items-center p-4">
            <li>
                <ButtonNavbarMenu :url="url.register">
                    S'inscrire
                </ButtonNavbarMenu>
            </li>
            <li>
                <ButtonLink color="gradient" :url="url.login">
                    Se connecter
                </ButtonLink>
            </li>
        </ul>
    </div>
</template>

<script setup>
import Icon from '../../icon/Icon.vue';
import ButtonLink from '../../button/ButtonLink.vue';
import ButtonNavbarMenu from '../../button/ButtonNavbarMenu.vue';
import NavbarUser from './NavbarUser.vue';

const props = defineProps({
    isConnected: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: {
            firstname: 'test',
            lastname: 'test',
            email: 'test@test.com'
        }
    },
    url: {
        type: Object,
        default: {
            login: '/',
            logout: '/',
            register: '/',
            home: '/',
            dashboard: '/',
            informations: '/'
        }
    }
})
</script>