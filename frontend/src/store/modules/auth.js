import { login } from "@/api/auth.js";
import { get_info } from "@/api/users.js";

export default {
    state: {
        isAuth: Boolean(localStorage.getItem("crm_token")),
    },
    mutations: {
        setIsAuth(state, payload) {
            state.isAuth = payload;
        },
    },
    actions: {
        setIsAuth({ commit }, payload) {
            commit("setIsAuth", payload);
        },
        async login({ dispatch }, payload) {
            const result = await login({
                login: payload.login,
                password: payload.password,
            });
            if (result) {
                await get_info(localStorage.getItem("crm_token"));
                dispatch("setLogin", JSON.parse(localStorage.getItem("user_data"))?.login);
                dispatch("setIsAuth", true);
                dispatch(
                    "setAvatar",
                    `${process.env.VUE_APP_API_URL}${JSON.parse(localStorage.getItem("user_data"))?.avatar}`
                );
                return true;
            }
            return false;
        },
    },
    getters: {
        getIsAuth: (state) => {
            return state.isAuth;
        },
    },
};
