const arrayMonth = [
    { slug: 'janvier', name: 'janvier', index: 1 },
    { slug: 'fevrier', name: 'février', index: 2 },
    { slug: 'mars', name: 'mars', index: 3 },
    { slug: 'avril', name: 'avril', index: 4 },
    { slug: 'mai', name: 'mai', index: 5 },
    { slug: 'juin', name: 'juin', index: 6 },
    { slug: 'juillet', name: 'juillet', index: 7 },
    { slug: 'aout', name: 'août', index: 8 },
    { slug: 'septembre', name: 'septembre', index: 9 },
    { slug: 'octobre', name: 'octobre', index: 10 },
    { slug: 'novembre', name: 'novembre', index: 11 },
    { slug: 'decembre', name: 'décembre', index: 12 }
];

const useDateFormat = () => {
    const toDay = (isoString) => {
        const date = new Date(isoString);
        const day = String(date.getDate()).padStart(2, '0');

        return parseInt(day, 10);
    };

    const toDayMonth = (isoString) => {
        const date = new Date(isoString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');

        return `${day}/${month}`;
    };
  
    const toFullDate = (isoString) => {
        const date = new Date(isoString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    const toFullDateFormatted = (isoString) => {
        const date = new Date(isoString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${day}/${month}/${year}`;
    };

    const getStringMonth = (slugMonth) => {
        const month = arrayMonth.find(m => m.slug === slugMonth);
        return month ? month.name : slugMonth;
    };

    const getSlugMonth = (indexMonth) => {
        const month = arrayMonth.find(m => m.index === indexMonth);
        return month ? month.slug : indexMonth;
    };

    const getIndexMonth = (slugMonth) => {
        const month = arrayMonth.find(m => m.slug === slugMonth);
        return month ? month.index : slugMonth;
    };
  
    return {
        toDay,
        toDayMonth,
        toFullDate,
        toFullDateFormatted,
        getStringMonth,
        getSlugMonth,
        getIndexMonth
    };
};

export default useDateFormat;