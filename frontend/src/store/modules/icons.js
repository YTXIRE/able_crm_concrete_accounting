import { get_all } from "@/api/icons.js";

export default {
    state: {
        icon: {
            id: 0,
            prefix: "",
            name: "",
        },
    },
    mutations: {
        setIcon(state, payload) {
            state.icon = {
                id: payload.id,
                prefix: payload.prefix,
                name: payload.name,
            };
        },
    },
    actions: {
        setIcon({ commit }, payload) {
            commit("setIcon", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllIcons({}, payload) {
            return await get_all(payload);
        },
    },
    getters: {
        getIcon(state) {
            return state.icon;
        },
    },
};
