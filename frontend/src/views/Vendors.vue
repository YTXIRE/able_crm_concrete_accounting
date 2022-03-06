<template>
    <Main>
        <h2>Поставщики</h2>
        <Vendors />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Vendors from "../components/Vendors/Vendors";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        Vendors,
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
