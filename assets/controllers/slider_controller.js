import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "value", "valueContainer"];

    connect() {
        this.updateValue();

        window.addEventListener("resize", this.updateValue.bind(this));
    }

    disconnect() {
        window.removeEventListener("resize", this.updateValue.bind(this));
    }

    updateValue() {
        const input = this.inputTarget;
        const value = this.valueTarget;

        value.textContent = input.value;

        setTimeout(() => {
            this.conditionalUnit()
            this.updateEmit()
        }, 500);

        this.resizeThumb()
    }

    conditionalUnit() {
        const input = this.inputTarget;
        const element = this.valueTarget;
        const unit = element.dataset.unit;

        if (unit === "year" && input.value == 1) {
            element.textContent = `${input.value} an`
        }
    }

    resizeThumb() {
        const input = this.inputTarget;
        const valueContainer = this.valueContainerTarget;

        const min = parseFloat(input.min);
        const max = parseFloat(input.max);
        const percent = (input.value - min) / (max - min);

        const thumbSize = 20;
        const inputWidth = input.clientWidth - thumbSize;

        const thumbPosition = percent * inputWidth + thumbSize / 2;

        const spanWidth = valueContainer.offsetWidth;
        const centeredPosition = thumbPosition - spanWidth / 2;

        valueContainer.style.left = `${centeredPosition}px`;
    }


    updateEmit() {
        const event = new Event("slider:update", {
            bubbles: true
        });

        document.dispatchEvent(event);
    }
}