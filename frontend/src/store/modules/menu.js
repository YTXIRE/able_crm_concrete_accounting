export default {
    state: {
        indexMenu: "1",
        activeSettings: Boolean(localStorage.getItem("currentMenu") === "0"),
        activeUserSettings: Boolean(localStorage.getItem("currentMenu") === "999"),
    },
    mutations: {
        setIndexMenu(state, payload) {
            state.indexMenu = payload;
        },
        setActiveSettings(state, payload) {
            state.activeSettings = payload;
        },
        setActiveUserSettings(state, payload) {
            state.activeUserSettings = payload;
        },
    },
    actions: {
        setIndexMenu({ commit }, payload) {
            commit("setIndexMenu", payload);
        },
        setActiveSettings({ commit }, payload) {
            commit("setActiveSettings", payload);
        },
        setActiveUserSettings({ commit }, payload) {
            commit("setActiveUserSettings", payload);
        },
    },
    getters: {
        getIndexMenu: (state) => {
            return state.indexMenu;
        },
        getActiveSettings: (state) => {
            return state.activeSettings;
        },
        getActiveUserSettings: (state) => {
            return state.activeUserSettings;
        },
    },
};
