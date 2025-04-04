<template>
        <button type="button" class="flex text-sm rounded-full text-stone-400 dark:text-stone-600 bg-stone-100 dark:bg-stone-200 border border-stone-200 dark:border-stone-700 focus:ring-4 focus:ring-stone-100 dark:focus:ring-stone-400/20" aria-expanded="false" data-dropdown-toggle="dropdown-user">
            <span class="sr-only">Open User Menu</span>
            <span class="w-8 h-8 flex items-center justify-center">
                <Icon class="size-5" name="User" />
            </span>
        </button>
        <div 
            id="dropdown-user"
            class="absolute flex flex-col hidden w-max divide-y divide-stone-200 dark:divide-stone-700 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
        >
            <div class="flex flex-row p-2">
                <span class="w-10 h-10 p-2 flex self-center justify-center align-content-center rounded-lg bg-stone-200 border border-stone-200 dark:border-stone-700">
                    <Icon class="size-5" name="User" />
                </span>
                <div class="flex flex-col text-stone-900 dark:text-stone-50 ml-2">
                    <span class="truncate font-semibold">{{ user.firstname }} {{ user.lastname }}</span>
                    <span class="truncate text-sm">{{ user.email }}</span>
                </div>
            </div>

            <div class="p-2 flex flex-col items-center justify-center">
                <ButtonThemeSwitch data-tooltip-target="tooltip-theme" data-tooltip-placement="bottom" />
                <div id="tooltip-theme" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                    Thème clair/sombre
                </div>
            </div>

            <div class="p-2">
                <a 
                    :href="url.dashboard"
                    class="flex items-center p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer"
                >
                    <span class="w-8 h-8 flex justify-center items-center">
                        <Icon class="size-5" name="LayoutDashboard" />
                    </span>
                    <span class="flex-1 ms-2 whitespace-nowrap">Dashboard</span>
                </a>
            </div>

            <div class="p-2">
                <a 
                    :href="url.logout"
                    class="flex items-center p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer"
                >
                    <span class="w-8 h-8 flex justify-center items-center">
                        <Icon class="size-5" name="LogOut" />
                    </span>
                    <span class="flex-1 ms-2 whitespace-nowrap">Se déconnecter</span>
                </a>
            </div>
        </div>
</template>

<script setup>
import ButtonThemeSwitch from '../../button/ButtonThemeSwitch.vue';
import Icon from '../../Icon.vue';

const props = defineProps({
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
            dashboard: '/',
            logout: '/'
        }
    },
});
</script>