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
  
    return {
        toDay,
        toDayMonth,
        toFullDate
    };
};

export default useDateFormat;