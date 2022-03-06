<template>
    <Main>
        <h2>Настройки пользователя</h2>
        <UserSettings />
    </Main>
</template>

<script>
import Main from "../components/Main";
import UserSettings from "../components/UserSettings/UserSettings";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        UserSettings,
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
