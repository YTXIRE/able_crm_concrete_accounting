import { change_password, create, delete_user, get_all, get_info, update_info } from "@/api/users.js";

export default {
    state: {
        login: JSON.parse(localStorage.getItem("user_data"))?.login ?? "user",
        isCreateUser: 0,
    },
    mutations: {
        setLogin(state, payload) {
            state.login = payload;
        },
        setIsCreateUser(state, payload) {
            state.isCreateUser = payload;
        },
    },
    actions: {
        setLogin({ commit }, payload) {
            commit("setLogin", payload);
        },
        setIsCreateUser({ commit }, payload) {
            commit("setIsCreateUser", payload);
        },
        async update_info({ dispatch }, payload) {
            const result = await update_info({
                login: payload.login,
                email: payload.email,
                id: payload.id,
                token: payload.token,
                password: payload.password,
                is_demo: payload.is_demo,
            });
            if (result) {
                await get_info(localStorage.getItem("crm_token"));
                dispatch("setLogin", JSON.parse(localStorage.getItem("user_data"))?.login);
                dispatch("setIsCreateUser", Math.random());
                return true;
            }
            return false;
        },
        // eslint-disable-next-line no-empty-pattern
        async change_password({}, payload) {
            const result = await change_password({
                password: payload.password,
                id: payload.id,
                token: payload.token,
            });
            return !!result;
        },
        // eslint-disable-next-line no-empty-pattern
        async get_all({}, payload) {
            return await get_all(payload);
        },
        async create({ dispatch }, payload) {
            const result = await create({
                token: payload.token,
                login: payload.login,
                email: payload.email,
                password: payload.password,
                is_demo: payload.is_demo,
            });
            if (result) {
                dispatch("setIsCreateUser", Math.random());
                return true;
            }
            return false;
        },
        async delete({ dispatch }, payload) {
            const result = await delete_user({
                token: payload.token,
                id: payload.id,
            });
            if (result) {
                dispatch("setIsCreateUser", Math.random());
                return true;
            }
            return false;
        },
    },
    getters: {
        getLogin: (state) => {
            return state.login;
        },
        getIsCreateUser: (state) => {
            return state.isCreateUser;
        },
    },
};
