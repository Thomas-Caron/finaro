export default function useConvertFilter() {
    const formatters = {
        currency: new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }),
        percent: new Intl.NumberFormat('fr-FR', {
            style: 'percent',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }),
        number: (decimals = 0) =>
            new Intl.NumberFormat('fr-FR', {
                style: 'decimal',
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
        }),
    };

    const convert = (number) => {
        const parsed = parseFloat(number);
        return Number.isFinite(parsed) ? parsed : 0;
    };

    const getCurrency = (number) => formatters.currency.format(convert(number));

    const getPercentage = (number) => {
        const value = convert(number) / 100;
        return formatters.percent.format(value);
    };

    const getYear = (number) => {
        const value = convert(number);
        const suffix = value === 1 ? 'an' : 'ans';
        return `${formatters.number(0).format(value)} ${suffix}`;
    };

    const getMonth = (number) => {
        const value = convert(number);
        return `${formatters.number(0).format(value)} mois`;
    };

    const formatNumber = (number, decimals = 0) => formatters.number(decimals).format(convert(number));

    return {
        getCurrency,
        getPercentage,
        getYear,
        getMonth,
        formatNumber,
    };
};