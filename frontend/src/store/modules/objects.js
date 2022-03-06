import { delete_object, get_all, restore, save, update } from "@/api/objects.js";

export default {
    state: {
        isNewObject: 0,
    },
    mutations: {
        setIsNewObject(state, payload) {
            state.isNewObject = payload;
        },
    },
    actions: {
        setIsNewObject({ commit }, payload) {
            commit("setIsNewObject", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllObjects({}, payload) {
            return await get_all(payload);
        },
        async saveObject({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewObject", Math.random());
                return result;
            }
            return false;
        },
        async updateObject({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewObject", Math.random());
                return result;
            }
            return false;
        },
        async deleteObject({ dispatch }, payload) {
            const result = await delete_object({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewObject", Math.random());
                return true;
            }
            return false;
        },
        async restoreObject({ dispatch }, payload) {
            const result = await restore({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewObject", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsNewObject(state) {
            return state.isNewObject;
        },
    },
};
