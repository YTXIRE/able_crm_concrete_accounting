import { get_base, get_advanced, save_filter, get_filters, delete_filter, update } from "@/api/report.js";

export default {
    state: {
        isNewFilter: 0,
    },
    mutations: {
        setIsNewFilter(state, payload) {
            state.isNewFilter = payload;
        },
    },
    actions: {
        setIsNewFilter({ commit }, payload) {
            commit("setIsNewFilter", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getBaseReport({}, payload) {
            return await get_base(payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAdvancedReport({}, payload) {
            return await get_advanced(payload);
        },
        async saveFilter({ dispatch }, payload) {
            const result = await save_filter(payload);
            if (result) {
                dispatch("setIsNewFilter", Math.random());
                return result;
            }
            return false;
        },
        // eslint-disable-next-line no-empty-pattern
        async getFilters({}, payload) {
            return await get_filters(payload);
        },
        async deleteFilter({ dispatch }, payload) {
            const result = await delete_filter({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewFilter", Math.random());
                return true;
            }
            return false;
        },
        async updateFilter({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewFilter", Math.random());
                return result;
            }
            return false;
        },
    },
    getters: {
        getIsNewFilter(state) {
            return state.isNewFilter;
        },
    },
};
