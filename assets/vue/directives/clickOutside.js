export default {
    beforeMount(el, binding) {
        el.clickOutsideEvent = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.addEventListener("pointerdown", el.clickOutsideEvent);
    },
    unmounted(el) {
        document.removeEventListener("pointerdown", el.clickOutsideEvent);
    }
};