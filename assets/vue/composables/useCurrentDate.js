const useCurrentDate = () => {
    const now = new Date();

    const dateNow = {
        month: now.toLocaleString('fr-FR', { month: 'long' }).toLowerCase(),
        year: now.getFullYear()
    };

    return {
        dateNow
    };
};

export default useCurrentDate;