import { upload_avatar } from "@/api/users.js";

export default {
    state: {
        avatar: `${process.env.VUE_APP_API_URL}${JSON.parse(localStorage.getItem("user_data"))?.avatar}` || null,
    },
    mutations: {
        setAvatar(state, payload) {
            state.avatar = payload;
        },
    },
    actions: {
        setAvatar({ commit }, payload) {
            commit("setAvatar", payload);
        },
        async upload_avatar({ dispatch }, payload) {
            const result = await upload_avatar({
                avatar: payload.avatar,
                id: payload.id,
                token: payload.token,
            });
            if (result) {
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
        getAvatar: (state) => {
            return state.avatar;
        },
    },
};
