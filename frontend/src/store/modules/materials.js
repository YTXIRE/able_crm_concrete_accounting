import { get_all, save, update, search } from "@/api/materials.js";

export default {
    state: {
        isNewMaterial: 0,
        isSearchMaterial: 0,
        search_word: ''
    },
    mutations: {
        setIsNewMaterial(state, payload) {
            state.isNewMaterial = payload;
        },
        setIsSearchMaterial(state, payload) {
            state.isSearchMaterial = payload;
        },
        setSearchWordMaterial(state, payload) {
            state.search_word = payload;
        },
    },
    actions: {
        setIsNewMaterial({ commit }, payload) {
            commit("setIsNewMaterial", payload);
        },
        setIsSearchMaterial({ commit }, payload) {
            commit("setIsSearchMaterial", payload);
        },
        setSearchWordMaterial({ commit }, payload) {
            commit("setSearchWordMaterial", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllMaterials({}, payload) {
            return await get_all(payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async searchMaterial({}, payload) {
            return await search(payload);
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
        getIsSearchMaterial(state) {
            return state.isSearchMaterial;
        },
        getSearchWordMaterial(state) {
            return state.search_word;
        },
    },
};
