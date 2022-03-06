<template>
    <Main>
        <h2>История операций</h2>
        <History />
    </Main>
</template>

<script>
import Main from "../components/Main";
import History from "../components/HistoryOperations/HistoryOperaions.vue";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        History,
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
