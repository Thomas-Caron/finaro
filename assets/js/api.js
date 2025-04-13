const apiBaseUrl = window.location.origin;

const api = {
    async get(route) {
        return api.request("GET", route);
    },

    async post(route, body = {}) {
        return api.request("POST", route, body);
    },

    async request(method, route, body = null) {
        try {
            const token = localStorage.getItem('apiToken');

            const options = {
                method,
                headers: {
                    "Content-Type": "application/json",
                    ...(token ? { "Authorization": `Bearer ${token}` } : {})
                }
            };

            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(`${apiBaseUrl}${route}`, options);

            const data = await response.json();

            if (!response.ok) {
                return { success: false, ...data };
            }

            return data;
        } catch (error) {
            console.error("API error :", error);
            throw error;
        }
    },
};

export default api;