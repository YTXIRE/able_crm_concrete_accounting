import {
    delete_history_operation,
    get_all_operations_by_material,
    get_material_by_object,
    get_objects_by_vendor,
    save,
    update,
} from "@/api/history_operation.js";

export default {
    state: {
        isNewHistoryOperation: 0,
    },
    mutations: {
        setIsNewHistoryOperation(state, payload) {
            state.isNewHistoryOperation = payload;
        },
    },
    actions: {
        // eslint-disable-next-line no-empty-pattern
        async getAllHistoryOperation({}, payload) {
            return await get_all_operations_by_material(payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllObjectsByVendorHistoryOperation({}, payload) {
            return await get_objects_by_vendor(payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllMaterialsByObjectsHistoryOperation({}, payload) {
            return await get_material_by_object(payload);
        },
        setIsNewHistoryOperation({ commit }, payload) {
            commit("setIsNewHistoryOperation", payload);
        },
        async saveHistoryOperation({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewHistoryOperation", Math.random());
                return result;
            }
            return false;
        },
        async updateHistoryOperation({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewHistoryOperation", Math.random());
                return result;
            }
            return false;
        },
        async deleteHistoryOperation({ dispatch }, payload) {
            const result = await delete_history_operation({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewHistoryOperation", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsNewHistoryOperation(state) {
            return state.isNewHistoryOperation;
        },
    },
};
