const useGenerateId = () => {
    const uniqueId = () => {
        return Math.floor(Math.random() * Date.now()).toString(16);
    }

    return {
        uniqueId
    };
};

export default useGenerateId;