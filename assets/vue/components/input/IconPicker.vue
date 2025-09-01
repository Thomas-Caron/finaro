<template>
    <div>
        <p 
            v-if="label"
            :class="[
                {
                    'text-red-700 dark:text-red-500': isError,
                    'text-stone-900 dark:text-stone-50': !isError
                },
                'block mb-2 text-sm font-medium'
            ]"
        >
            {{ label }}<span v-if="required" class="text-red-500 ms-2">*</span>
        </p>

        <div class="h-[38px] w-9">
            <button
                class="w-full h-full rounded-lg flex items-center justify-center border border-stone-300 dark:border-stone-500 cursor-pointer"
                :data-dropdown-toggle="`dropdown-icon-picker-${id}`"
                data-dropdown-placement="bottom"
            >
                <Icon :name="modelValue" class="size-5 text-stone-500 dark-text-stone-400" />
            </button>

            <div
                :id="`dropdown-icon-picker-${id}`"
                class="absolute mt-2 flex flex-col hidden items-start z-10 w-max p-2 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
            >
                <div class="grid grid-cols-10 gap-2">
                    <div
                        v-for="icon in iconSet"
                        :key="icon"
                        @click="selectIcon(icon)"
                        class="w-8 h-8 flex items-center justify-center rounded-lg cursor-pointer border-2 border-stone-300 dark:border-stone-700"
                        :class="modelValue === icon ? 'ring-2 ring-stone-400 dark:ring-stone-600' : 'hover:ring-2 hover:ring-stone-400 dark:hover:ring-stone-600'"
                    >
                        <Icon :name="icon" class="size-5 text-stone-500 dark:text-stone-400" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Icon from '../icon/Icon';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    modelValue: { type: String, default: '' },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});

const iconSet = [
    // 🛒 Dépenses courantes
    'ShoppingCart', 'ShoppingBasket', 'Shirt', 'Refrigerator', // → courses, achats du quotidien
	'Utensils', 'UtensilsCrossed', // → nourriture/restaurants
	'Fuel', // (n’existe pas exactement, tu peux prendre gas-pump) → essence / station-service
	'Car', // → voiture (entretien, assurance, etc.)
	'House', // → logement / loyer / charges
	'Lightbulb', 'Zap', // → électricité / énergie
	'Droplets', // → eau

    // 🎉 Vie perso & loisirs
	'TreePalm', // → vacances
	'Wine', 'Beer', // → sorties / resto / alcool
	'Tv', 'Gamepad2', 'Clapperboard', // → divertissement
	'Music', // → concerts, abonnements musicaux

    // 💼 Pro & administratif
	'Briefcase', // → dépenses pro
	'GraduationCap', // → éducation / formation
	'Activity', 'HeartPulse', 'Cross', // → santé / mutuelle
	'Bus', // → transports en commun
	'Phone', 'Smartphone', // → forfait téléphonique / internet

    //💰 Revenus & finances
    'Wallet', // → portefeuille, argent liquide
	'CreditCard', // → paiements CB
	'Euro', // → revenus / salaires
	'PiggyBank', // → épargne
];

const selectIcon = (icon) => {
    emit('update:modelValue', icon);
    document.activeElement.blur();
    document.body.click();
};
</script>