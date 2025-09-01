const useColor = () => {
    const getContrastColor = (hex) => {
        if (!hex) return 'text-stone-700';

        hex = hex.replace('#', '');

        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);

        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

        return luminance > 0.5 ? 'text-stone-700' : 'text-stone-400';
    };

    return {
        getContrastColor
    };
}

export default useColor;