<template>
    <ul class="p-3 space-y-2 font-medium">
        <li>
            <button 
                type="button"
                :class="[
                    { 'p-2 hover:bg-stone-200/30 dark:hover:bg-stone-700/40': !isCollapsed },
                    'flex justify-center items-center w-full text-base text-stone-700 transition duration-75 rounded-lg cursor-pointer dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-300'
            ]"
                data-dropdown-toggle="dropdown-user"
                data-dropdown-placement="right"
            >
                <span class="sr-only">Open User Menu</span>
                <span 
                    :class="[
                        { 'hover:bg-stone-300 transition duration-75': isCollapsed },
                        'w-10 h-10 p-2 flex justify-center align-content-center rounded-lg bg-stone-200 border border-stone-200 dark:border-stone-700'
                    ]"
                >
                    <Icon class="size-5 text-stone-700" name="User" />
                </span>
                <div v-if="!isCollapsed" class="grid flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">
                    <span class="truncate font-semibold">{{ user.firstname }} {{ user.lastname }}</span>
                    <span class="truncate text-sm">{{ user.email }}</span>
                </div>
                <Icon v-if="!isCollapsed" class="size-5" name="ChevronsUpDown" />
            </button>
            <div
                id="dropdown-user"
                :class="[
                    {
                        'transform -translate-x-full': sidebar.isSmallScreen
                    },
                    'absolute flex flex-col hidden w-max divide-y divide-stone-200 dark:divide-stone-700 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800'
                ]"
            >
                <div class="flex flex-row p-2">
                    <span class="w-10 h-10 p-2 flex self-center justify-center align-content-center rounded-lg bg-stone-200 border border-stone-200 dark:border-stone-700">
                        <Icon class="size-5 text-stone-700" name="User" />
                    </span>
                    <div class="flex flex-col ml-2 text-stone-700 dark:text-stone-400">
                        <span class="truncate font-semibold">{{ user.firstname }} {{ user.lastname }}</span>
                        <span class="truncate text-sm">{{ user.email }}</span>
                    </div>
                </div>
                <div class="p-2 flex flex-col items-center justify-center">
                    <ButtonThemeSwitch tooltip-class="!left-20"/>
                </div>
                <div class="p-2">
                    <a 
                        :href="url.logout"
                        class="flex items-center p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer"
                        @click.prevent="handleLogout"
                    >
                        <span class="w-8 h-8 flex justify-center items-center">
                            <Icon class="size-5" name="LogOut" />
                        </span>
                        <span class="flex-1 ms-2 whitespace-nowrap">Se déconnecter</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</template>

<script setup>
import ButtonThemeSwitch from '../../button/ButtonThemeSwitch.vue';
import Icon from '../../icon/Icon.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore.js';

const sidebar = useSidebarStore();

const props = defineProps({
    isCollapsed: { type: Boolean, default: false },
    user: { type: Object, default: () => ({}) },
    url: { type: Object, default: () => ({}) }
});

const handleLogout = () => {
    localStorage.removeItem('apiToken');
    window.location.href = props.url.logout;
};
</script>