<template>
    <ul class="flex-1 px-3 py-6 space-y-2 font-medium">
        
        <SidebarNavItem
            :data="data.dashboard"
            :is-collapsed="isCollapsed"
        />

        <SidebarNavDropdown
            :data="data.account"
            :is-collapsed="isCollapsed"
        />

        <SidebarNavDropdown
            :data="data.simulator"
            :is-collapsed="isCollapsed"
        />
    </ul>
</template>

<script setup>
import SidebarNavDropdown from './SidebarNavDropdown.vue';
import SidebarNavItem from './SidebarNavItem.vue';

const props = defineProps({
    isCollapsed: {
        type: Boolean,
        default: false
    },
    data: {
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
</script>