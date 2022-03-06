<template>
    <Main>
        <h2>Типы материалов</h2>
        <MaterialTypes />
    </Main>
</template>

<script>
import Main from "../components/Main";
import MaterialTypes from "../components/MaterialTypes/MaterialTypes";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        MaterialTypes,
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
