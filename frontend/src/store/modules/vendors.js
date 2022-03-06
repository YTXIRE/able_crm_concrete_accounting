import { delete_vendor, get_all, restore, save, update } from "@/api/vendors.js";

export default {
    state: {
        isNewVendor: 0,
    },
    mutations: {
        setIsNewVendor(state, payload) {
            state.isNewVendor = payload;
        },
    },
    actions: {
        setIsNewVendor({ commit }, payload) {
            commit("setIsNewVendor", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllVendors({}, payload) {
            return await get_all(payload);
        },
        async saveVendor({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewVendor", Math.random());
                return result;
            }
            return false;
        },
        async updateVendor({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewVendor", Math.random());
                return result;
            }
            return false;
        },
        async deleteVendor({ dispatch }, payload) {
            const result = await delete_vendor({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewVendor", Math.random());
                return true;
            }
            return false;
        },
        async restoreVendor({ dispatch }, payload) {
            const result = await restore({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewVendor", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsNewVendor(state) {
            return state.isNewVendor;
        },
    },
};
