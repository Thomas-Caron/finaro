<template>
    <div class="bg-stone-50 dark:bg-stone-900 border-t border-stone-200 dark:border-stone-700">
        <div class="container mx-auto p-4 lg:py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <a :href="url.home" class="flex items-center mb-4 sm:mb-0">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-stone-50">Finaro</span>
                </a>
                <ul class="flex flex-col ">
                    <li>
                        <ButtonMenuLink :url="url.contact">
                            Contact
                        </ButtonMenuLink>
                    </li>
                    <li>
                        <ButtonMenuLink :url="url.terms">
                            Conditions d'utilisation
                        </ButtonMenuLink>
                    </li>
                    <li>
                        <ButtonMenuLink :url="url.privacy">
                            Politique de confidentialité
                        </ButtonMenuLink>
                    </li>
                </ul>
            </div>
            <hr class="my-4 border-stone-200 sm:mx-auto dark:border-stone-700 lg:my-8" />
            <span class="block text-sm text-stone-900 text-center dark:text-stone-50">© 2025 - Finaro. Made by <a class="hover:underline" href="https://thomascaron.com" target="_blank">Thomas CARON</a></span>
        </div>
    </div>
</template>

<script setup>
import ButtonMenuLink from '../../button/ButtonNavbarMenu.vue';

const props = defineProps({
    url: { type: Object, default: () => ({}) }
})
</script>