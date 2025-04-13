<template>
    <aside
        id="sidebar"
        :class="[
            {
                'w-64': !sidebar.isCollapsed,
                'w-20': sidebar.isCollapsed
            },
            'fixed top-0 left-0 z-40 h-svh transition-[width] duration-300 bg-stone-50 border-r border-stone-200 dark:bg-stone-900 dark:border-stone-700'
        ]"
        aria-label="Sidebar"
    >
        <div class="flex flex-col h-full overflow-y-auto bg-stone-50 dark:bg-stone-900 divide-y divide-stone-200 dark:divide-stone-700">

            <button
                type="button"
                :class="[
                    {
                        'cursor-e-resize': sidebar.isCollapsed,
                        'cursor-w-resize': !sidebar.isCollapsed
                    },
                    'absolute w-1 top-0 -right-1 h-full bg-transparent hover:bg-stone-200 dark:hover:bg-stone-700'
                ]"
                @click="sidebar.toggle()"
            >
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <a 
                href="/" 
                :class="[
                    {
                        'ps-8': !sidebar.isCollapsed,
                        'justify-center': sidebar.isCollapsed
                    },
                    'flex py-6'
                ]"
            >
                <span class="text-2xl font-semibold text-stone-900 whitespace-nowrap dark:text-stone-50">
                    {{ 
                        sidebar.isCollapsed
                        ? 'F.'
                        : 'Finaro'
                    }}
                </span>
            </a>

            <SidebarNav :is-collapsed="sidebar.isCollapsed" :nav="nav" />

            <SidebarUser :is-collapsed="sidebar.isCollapsed" :user="user" :url="url" />
        </div>
    </aside>

    <div
        :class="[
            {
                'ml-64': !sidebar.isCollapsed,
                'ml-20': sidebar.isCollapsed
            },
            'p-6 flex flex-row'
        ]"
    >
        <button
            type="button"
            class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-600 cursor-pointer"
            @click="sidebar.toggle()"
        >
            <span class="sr-only">Toggle sidebar</span>
            <Icon class="size-6 text-stone-700 dark:text-stone-300" name="PanelLeft" />
        </button>

        <Breadcrumb />
    </div>
</template>

<script setup>
import Breadcrumb from '../breadcrumb/Breadcrumb.vue';
import Icon from '../../Icon.vue';
import SidebarNav from './SidebarNav.vue';
import SidebarUser from './SidebarUser.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore';

const props = defineProps({
    user: {
        type: Object,
        default: () => ({
            firstname: 'test',
            lastname: 'test',
            email: 'test@test.com'
        })
    },
    url: {
        type: Object,
        default: () => ({
            logout: '/',
            dashboard: '/'
        })
    },
    nav: {
        type: Object,
        default: () => ({
            dashboard: {
                name: {
                    slug: 'dashboard',
                    text: 'Dashboard',
                    url: '/'
                },
                icon: 'LayoutDashboard',
            },
            account: {
                name: {
                    slug: 'account',
                    text: 'Comptes',
                },
                subName: [
                    {
                        slug: 'current',
                        text: 'Compte courant',
                        url: '/'
                    },
                    {
                        slug: 'common',
                        text: 'Compte commun',
                        url: '/'
                    }
                ],
                icon: 'Coins'
            },
            simulator: {
                name: {
                    slug: 'simulator',
                    text: 'Simulateurs',
                },
                subName: [
                    {
                        slug: 'mortgageLoan',
                        text: 'Crédit immobilier',
                        url: '/'
                    },
                    {
                        slug: 'compoundInterest',
                        text: 'Intérêts composés',
                        url: '/'
                    }
                ],
                icon: 'Calculator'
            }
        })
    }
});

const sidebar = useSidebarStore();
</script>
