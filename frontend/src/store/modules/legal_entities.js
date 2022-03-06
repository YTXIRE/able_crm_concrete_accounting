import { delete_legal_entity, get_all, restore, save, update } from "@/api/legal_entities.js";

export default {
    state: {
        isLegalEntities: 0,
    },
    mutations: {
        setIsLegalEntities(state, payload) {
            state.isLegalEntities = payload;
        },
    },
    actions: {
        // eslint-disable-next-line no-empty-pattern
        async getAllLegalEntities({}, payload) {
            return await get_all(payload);
        },
        setIsLegalEntities({ commit }, payload) {
            commit("setIsLegalEntities", payload);
        },
        async saveLegalEntities({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsLegalEntities", Math.random());
                return result;
            }
            return false;
        },
        async updateLegalEntities({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsLegalEntities", Math.random());
                return result;
            }
            return false;
        },
        async deleteLegalEntity({ dispatch }, payload) {
            const result = await delete_legal_entity({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsLegalEntities", Math.random());
                return true;
            }
            return false;
        },
        async restoreLegalEntity({ dispatch }, payload) {
            const result = await restore({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsLegalEntities", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsLegalEntities(state) {
            return state.isLegalEntities;
        },
    },
};
