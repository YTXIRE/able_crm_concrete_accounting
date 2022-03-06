import { delete_payment, get_all, save, update } from "@/api/payments.js";

export default {
    state: {
        isNewPayments: 0,
        legalEntityId: "",
    },
    mutations: {
        setIsNewPayment(state, payload) {
            state.isNewPayments = payload;
        },
        setLegalEntityId(state, payload) {
            state.legalEntityId = payload;
        },
    },
    actions: {
        setIsNewPayment({ commit }, payload) {
            commit("setIsNewPayment", payload);
        },
        setLegalEntityId({ commit }, payload) {
            commit("setLegalEntityId", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getAllPayments({}, payload) {
            return await get_all(payload);
        },
        async savePayment({ dispatch }, payload) {
            const result = await save(payload);
            if (result) {
                dispatch("setIsNewPayment", Math.random());
                return result;
            }
            return false;
        },
        async updatePayment({ dispatch }, payload) {
            const result = await update(payload);
            if (result) {
                dispatch("setIsNewPayment", Math.random());
                return result;
            }
            return false;
        },
        async deletePayment({ dispatch }, payload) {
            const result = await delete_payment({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsNewPayment", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsNewPayment(state) {
            return state.isNewPayments;
        },
        getLegalEntityId(state) {
            return state.legalEntityId;
        },
    },
};
