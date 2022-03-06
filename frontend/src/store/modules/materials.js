import { get_all, save, update } from "@/api/materials.js";

export default {
    state: {
        isNewMaterial: 0,
    },
    mutations: {
        setIsNewMaterial(state, payload) {
            state.isNewMaterial = payload;
        },
    },
    actions: {
        setIsNewMaterial({ commit }, payload) {
            commit("setIsNewMaterial", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllMaterials({}, payload) {
            return await get_all(payload);
        },
        async saveMaterial({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewMaterial", Math.random());
                return result;
            }
            return false;
        },
        async updateMaterial({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewMaterial", Math.random());
                return result;
            }
            return false;
        },
    },
    getters: {
        getIsNewMaterial(state) {
            return state.isNewMaterial;
        },
    },
};
