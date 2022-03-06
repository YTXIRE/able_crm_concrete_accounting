<template>
    <Main>
        <h2>Настройки приложения</h2>
        <Settings />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Settings from "../components/Settings/Settings";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        Settings,
    },
    data() {
        return {};
    },
    methods: {},
    beforeMount() {
        document.title = this.title;
    },
};
</script>

<style scoped></style>
