<template>
    <Main>
        <h2>Список материалов</h2>
        <Materials />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Materials from "../components/Materials/Materials";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        Materials,
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
