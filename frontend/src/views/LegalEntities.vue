<template>
    <Main>
        <h2>Юридические лица</h2>
        <LegalEntities />
    </Main>
</template>

<script>
import Main from "../components/Main";
import LegalEntities from "../components/LegalEntities/LegalEntities";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        LegalEntities,
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
