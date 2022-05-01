<template>
    <Wrapper>
        <router-view />
    </Wrapper>
    <div id="nav">
        <router-link to="/"></router-link>
        <router-link to="/dashboard"></router-link>
        <router-link to="/vendors"></router-link>
        <router-link to="/payments"></router-link>
        <router-link to="/settings"></router-link>
        <router-link to="/user_settings"></router-link>
        <router-link to="/materials"></router-link>
        <router-link to="/material_types"></router-link>
        <router-link to="/legal_entities"></router-link>
        <router-link to="/history"></router-link>
        <router-link to="/reports"></router-link>
    </div>
</template>

<script>
import Wrapper from "./views/Wrapper";
import { mapActions } from "vuex";

export default {
    components: {
        Wrapper,
    },
    data() {
        return {};
    },
    methods: {
        ...mapActions(["setIndexMenu"]),
    },
    mounted() {
        if (localStorage.getItem("crm_token") === null) {
            this.$router.push("/");
        } else {
            if (localStorage.getItem('currentLink') !== null) {
                this.$router.push(localStorage.getItem('currentLink'));
                console.log(localStorage.getItem('currentMenu'));
                this.setIndexMenu(localStorage.getItem('currentMenu'))
            } else {
                this.$router.push("/dashboard");
            }
        }
    },
};
</script>

<style>
@font-face {
    font-family: "Roboto-Black";
    src: url("./assets/fonts/Roboto-Black.ttf") format("truetype");
    font-style: normal;
    font-weight: 900;
}

@font-face {
    font-family: "Roboto-BlackItalic";
    src: url("./assets/fonts/Roboto-BlackItalic.ttf") format("truetype");
    font-style: italic;
    font-weight: 900;
}

@font-face {
    font-family: "Roboto-Bold";
    src: url("./assets/fonts/Roboto-Bold.ttf") format("truetype");
    font-style: normal;
    font-weight: 700;
}

@font-face {
    font-family: "Roboto-BoldItalic";
    src: url("./assets/fonts/Roboto-BoldItalic.ttf") format("truetype");
    font-style: italic;
    font-weight: 700;
}

@font-face {
    font-family: "Roboto-Italic";
    src: url("./assets/fonts/Roboto-Italic.ttf") format("truetype");
    font-style: normal;
    font-weight: normal;
}

@font-face {
    font-family: "Roboto-Light";
    src: url("./assets/fonts/Roboto-Light.ttf") format("truetype");
    font-style: normal;
    font-weight: 300;
}

@font-face {
    font-family: "Roboto-LightItalic";
    src: url("./assets/fonts/Roboto-LightItalic.ttf") format("truetype");
    font-style: italic;
    font-weight: 300;
}

@font-face {
    font-family: "Roboto-Medium";
    src: url("./assets/fonts/Roboto-Medium.ttf") format("truetype");
    font-style: normal;
    font-weight: 500;
}

@font-face {
    font-family: "Roboto-MediumItalic";
    src: url("./assets/fonts/Roboto-MediumItalic.ttf") format("truetype");
    font-style: italic;
    font-weight: 500;
}

@font-face {
    font-family: "Roboto-Regular";
    src: url("./assets/fonts/Roboto-Regular.ttf") format("truetype");
    font-style: normal;
    font-weight: 400;
}

@font-face {
    font-family: "Roboto-Thin";
    src: url("./assets/fonts/Roboto-Thin.ttf") format("truetype");
    font-style: normal;
    font-weight: 100;
}

@font-face {
    font-family: "Roboto-ThinItalic";
    src: url("./assets/fonts/Roboto-ThinItalic.ttf") format("truetype");
    font-style: normal;
    font-weight: italic;
}

body {
    background: #e9eef3;
    margin: 0;
    padding: 0;
}

#app {
    font-family: "Roboto-Regular";
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
}

#app h2 {
    margin-top: -20px;
}

.el-select-dropdown__wrap {
    max-height: 500px !important;
}

.el-date-editor.el-input,
.el-date-editor.el-input__inner {
    width: 100% !important;
}

::-webkit-scrollbar {
    width: 5px !important;
}

::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px #2c3e50 !important;
    -webkit-border-radius: 3px !important;
    border-radius: 3px !important;
}

::-webkit-scrollbar-thumb {
    -webkit-border-radius: 3px !important;
    border-radius: 3px !important;
    background: #e9eef3 !important;
    -webkit-box-shadow: inset 0 0 6px #2c3e50 !important;
}

::-webkit-scrollbar-thumb:window-inactive {
    background: #e9eef3 !important;
}

html {
    overflow-y: scroll;
}

.el-notification {
    width: auto !important;
    max-width: 800px !important;
}

.el-dialog {
    text-align: center !important;
}

.btn_footer,
.el-dialog__footer {
    box-sizing: border-box !important;
    text-align: right !important;
}

.danger_docs {
    --el-table-tr-background-color: #ffcbcb !important;
}

.el-dialog__body {
    padding: 10px !important;
}

.el-table tr {
    background-color: transparent !important;
}
</style>
