<template>
    <Main>
        <h2>Оплата</h2>
        <Payments />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Payments from "../components/Payments/Payments.vue";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    components: {
        Main,
        Payments,
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
