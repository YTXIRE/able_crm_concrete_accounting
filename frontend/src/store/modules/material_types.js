import { get_all, save, update } from "@/api/material_types.js";

export default {
    state: {
        isNewMaterialType: 0,
    },
    mutations: {
        setIsNewMaterialType(state, payload) {
            state.isNewMaterialType = payload;
        },
    },
    actions: {
        setIsNewMaterialType({ commit }, payload) {
            commit("setIsNewMaterialType", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllMaterialTypes({}, payload) {
            return await get_all(payload);
        },
        async saveMaterialType({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewMaterialType", Math.random());
                return result;
            }
            return false;
        },
        async updateMaterialType({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewMaterialType", Math.random());
                return result;
            }
            return false;
        },
    },
    getters: {
        getIsNewMaterialType(state) {
            return state.isNewMaterialType;
        },
    },
};
