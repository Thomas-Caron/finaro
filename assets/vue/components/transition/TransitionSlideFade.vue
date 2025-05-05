<template>
    <Transition name="slide-fade">
        <slot></slot>
    </Transition>
</template>

<script setup>
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
    transition: all 0.4s ease-in;
}
.slide-fade-leave-active {
    transition: all 0.2s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(60px);
    opacity: 0;
}

.slide-fade-enter-to,
.slide-fade-leave-from {
    transform: translateY(0);
    opacity: 100;
}
</style>