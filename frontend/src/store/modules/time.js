import { get_timezone, get_timezones, set_timezone } from "@/api/time.js";
import { get_info } from "@/api/users.js";

export default {
    state: {
        timezone: JSON.parse(localStorage.getItem("user_data"))?.timezone,
    },
    mutations: {
        setTimezone(state, payload) {
            state.timezone = payload;
        },
    },
    actions: {
        setTimezone({ commit }, payload) {
            commit("setTimezone", payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async getTimezones({}, payload) {
            return await get_timezones(payload);
        },
        async setUserTimezone({ dispatch }, payload) {
            const result = await set_timezone(payload);
            if (result) {
                await get_info(localStorage.getItem("crm_token"));
                dispatch("setTimezone", JSON.parse(localStorage.getItem("user_data"))?.timezone);
            }
        },
        // eslint-disable-next-line no-empty-pattern
        async getTimezone({}, payload) {
            return await get_timezone(payload);
        },
    },
    getters: {
        getTimezone: (state) => {
            return state.isAuth;
        },
    },
};
