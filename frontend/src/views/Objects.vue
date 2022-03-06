<template>
    <Main>
        <h2>Список объектов</h2>
        <Objects />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Objects from "../components/Objects/Objects";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        Objects,
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
