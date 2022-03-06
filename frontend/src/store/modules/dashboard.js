import { get_data } from "@/api/dashboard.js";

export default {
    state: {
        period: "30",
        isUpdated: 0,
        isUpdatedDate: 0,
        date_from: 0,
        date_to: Math.floor(new Date().getTime() / 1000),
    },
    mutations: {
        setPeriod(state, payload) {
            state.period = payload;
        },
        setIsUpdated(state, payload) {
            state.isUpdated = payload;
        },
        setIsUpdatedDate(state, payload) {
            state.isUpdatedDate = payload;
        },
        setDateFrom(state, payload) {
            state.date_from = payload;
        },
        setDateTo(state, payload) {
            state.date_to = payload;
        },
    },
    actions: {
        setPeriod({ commit }, payload) {
            commit("setPeriod", payload);
        },
        setIsUpdated({ commit }, payload) {
            commit("setIsUpdated", payload);
        },
        setIsUpdatedDate({ commit }, payload) {
            commit("setIsUpdatedDate", payload);
        },
        setDateFrom({ commit }, payload) {
            commit("setDateFrom", payload);
        },
        setDateTo({ commit }, payload) {
            commit("setDateTo", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getDataDashboard({}, payload) {
            return await get_data(payload);
        },
    },
    getters: {
        getPeriod: (state) => {
            return state.period;
        },
        getIsUpdated: (state) => {
            return state.isUpdated;
        },
        getIsUpdatedDate: (state) => {
            return state.isUpdatedDate;
        },
        getDateFrom: (state) => {
            return state.date_from;
        },
        getDateTo: (state) => {
            return state.date_to;
        },
    },
};
