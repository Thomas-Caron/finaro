import useDateFormat from "./useDateFormat";'../composables/useDateFormat';

const useCurrentDate = () => {
    const { getSlugMonth } = useDateFormat();
    const now = new Date();

    const dateNow = {
        month: getSlugMonth(now.getMonth() + 1),
        year: now.getFullYear()
    };

    return {
        dateNow
    };
};

export default useCurrentDate;