<template>
    <div class="container mx-auto my-6">
        <div class="flex flex-col md:flex-row gap-8 items-stretch min-h-[400px] lg:min-h-[500px]">
            <div class="md:w-2/5 lg:w-1/2 flex flex-col justify-center gap-8">
                <h1 class="text-3xl font-bold text-center md:text-left text-stone-950 dark:text-stone-50">
                    Savez-vous combien il vous reste jusqu'à la fin du mois ?
                </h1>
                <h2 class="text-lg text-center md:text-left text-stone-950 dark:text-stone-50">
                    Suivez vos revenus et dépenses en temps réel, et découvrez instantanément votre solde prévisionnel jusqu'à la fin du mois.
                </h2>
                <ButtonLink class="self-center md:self-start" color="gradient" :url="url.dashboard">
                    Démarrer gratuitement
                </ButtonLink>
            </div>

            <div class="md:w-3/5 lg:w-1/2 flex flex-col items-center justify-center">
                <img src="/build/images/homepage/mockup-light.png" class="dark:hidden object-contain" alt="Mockup clair">
                <img src="/build/images/homepage/mockup-dark.png" class="hidden dark:block object-contain" alt="Mockup sombre">
            </div>
        </div>
    </div>

    <div class="bg-stone-50 dark:bg-stone-900">
        <div class="container mx-auto py-6">
            <h3 class="text-lg font-bold text-center text-stone-950 dark:text-stone-50">
                Bénéfices clés
            </h3>

            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mt-6">
                <div class="rounded-lg flex flex-col gap-3 p-4 text-stone-500 dark:text-stone-400 bg-stone-100/40 dark:bg-stone-800/40 border border-stone-200 dark:border-stone-700">
                    <Icon class="size-8 p-2 text-stone-600 dark:text-stone-200 rounded-sm bg-stone-300 dark:bg-stone-700/40" name="Calendar" />
                    <p class="text-normal font-bold">Projection en temps réel</p>
                    <p class="text-md">Découvrez instantanément votre solde prévisionnel fin de mois</p>
                </div>
                
                <div class="rounded-lg flex flex-col gap-3 p-4 text-stone-500 dark:text-stone-400 bg-stone-100/40 dark:bg-stone-800/40 border border-stone-200 dark:border-stone-700">
                    <Icon class="size-8 p-2 text-stone-600 dark:text-stone-200 rounded-sm bg-stone-300 dark:bg-stone-700/40" name="Zap" />
                    <p class="text-normal font-bold">Saisie ultra-rapide </p>
                    <p class="text-md">Ajoutez revenus et dépenses en quelques secondes</p>
                </div>
                <div class="rounded-lg flex flex-col gap-3 p-4 text-stone-500 dark:text-stone-400 bg-stone-100/40 dark:bg-stone-800/40 border border-stone-200 dark:border-stone-700">
                    <Icon class="size-8 p-2 text-stone-600 dark:text-stone-200 rounded-sm bg-stone-300 dark:bg-stone-700/40" name="Brain" />
                    <p class="text-normal font-bold">Prévisions intelligentes</p>
                    <p class="text-md">Anticipez vos prélèvements automatiques restants</p>
                </div>
                <div class="rounded-lg flex flex-col gap-3 p-4 text-stone-500 dark:text-stone-400 bg-stone-100/40 dark:bg-stone-800/40 border border-stone-200 dark:border-stone-700">
                    <Icon class="size-8 p-2 text-stone-600 dark:text-stone-200 rounded-sm bg-stone-300 dark:bg-stone-700/40" name="Eye" />
                    <p class="text-normal font-bold">Interface intuitive</p>
                    <p class="text-md">Tout comprendre en un coup d'œil, sans comptabilité complexe</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto my-6">
        <h3 class="text-lg font-bold text-center text-stone-950 dark:text-stone-50">
            Comment ça marche ?
        </h3>

        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 mt-6 ps-4 items-stretch">
            <ol class="relative text-stone-600 dark:text-stone-400 border-s border-stone-200 dark:border-stone-700">                  
                <li class="mb-10 ms-8 hover:text-stone-950 hover:dark:text-stone-50 transition duration-300">            
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 border border-stone-200 dark:border-stone-700 bg-white dark:bg-dark ring-8 ring-white dark:ring-dark">
                        1
                    </span>
                    <p class="mb-4 pt-1 text-base font-normal">Renseignez le restant du mois précédent.</p>
                </li>
                <li class="mb-10 ms-8 hover:text-stone-950 hover:dark:text-stone-50 transition duration-300">  
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 border border-stone-200 dark:border-stone-700 bg-white dark:bg-dark ring-8 ring-white dark:ring-dark">
                        2
                    </span>
                    <p class="mb-4 pt-1 text-base font-normal">Renseignez vos revenus du mois</p>
                </li>
                <li class="mb-10 ms-8 hover:text-stone-950 hover:dark:text-stone-50 transition duration-300">  
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 border border-stone-200 dark:border-stone-700 bg-white dark:bg-dark ring-8 ring-white dark:ring-dark">
                        3
                    </span>
                    <p class="mb-4 pt-1 text-base font-normal">Ajoutez vos charges fixes et dépenses</p>
                </li>
                <li class="ms-8 hover:text-stone-950 hover:dark:text-stone-50 transition duration-300">  
                    <span class="absolute flex items-center justify-center w-8 h-8 rounded-full -start-4 border border-stone-200 dark:border-stone-700 bg-white dark:bg-dark ring-8 ring-white dark:ring-dark">
                        4
                    </span>
                    <p class="mb-4 pt-1 text-base font-normal">Consultez votre solde prévisionnel en temps réel</p>
                </li>
            </ol>

            <div class="flex flex-col items-center justify-bottom max-h-[264px] overflow-hidden">
                <img src="/build/images/homepage/create-mockup-light.png" class="block dark:hidden max-h-[352px]" alt="Mockup clair">
                <img src="/build/images/homepage/create-mockup-dark.png" class="hidden dark:block max-h-[352px]" alt="Mockup sombre">
            </div>
        </div>
    </div>

    <div class="bg-stone-50 dark:bg-stone-900">
        <div class="container mx-auto py-6">
            <h3 class="text-lg font-bold text-center text-stone-950 dark:text-stone-50">
                Vous reconnaissez-vous ?
            </h3>

            <div class="grid gap-6 grid-cols-1 md:grid-cols-2 mt-6">
                <div class="flex flex-col items-center justify-bottom max-h-[230px] overflow-hidden">
                    <img src="/build/images/homepage/expense-mockup-light.png" class="block dark:hidden max-h-[360px]" alt="Mockup clair">
                    <img src="/build/images/homepage/expense-mockup-dark.png" class="hidden dark:block max-h-[360px]" alt="Mockup sombre">
                </div>

                <ol class="flex flex-col justify-between text-base font-normal text-stone-950 dark:text-stone-50">
                    <li class="">❌ Je ne sais jamais combien il me reste à la fin du mois</li>
                    <li class="">❌ Je découvre mes fins de mois difficiles trop tard</li>
                    <li class="">❌ J'oublie mes prélèvements automatiques</li>
                    <li class="">✅ Avec Finaro, vous savez toujours où vous en êtes</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container mx-auto my-6">
        <div class="flex flex-col justify-between items-center gap-6">
            <h3 class="text-lg font-bold text-center text-stone-950 dark:text-stone-50">
                Prenez le contrôle de vos finances, mois après mois.
            </h3>

            <ButtonLink color="gradient" :url="url.dashboard">
                Démarrer gratuitement
            </ButtonLink>
        </div>
    </div>

</template>

<script setup>
import { onMounted } from 'vue';
import ButtonLink from '../../components/button/ButtonLink.vue';
import Icon from '../../components/icon/Icon.vue';

const props = defineProps({
    apiToken: { type: String, default: null },
    url: { type: Object, default: () => ({}) }
});

onMounted(() => {
    if (props.apiToken && props.apiToken.trim() !== '') {
        localStorage.setItem('apiToken', props.apiToken);

        const url = new URL(window.location.href);
        url.searchParams.delete('token');
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
});
</script>