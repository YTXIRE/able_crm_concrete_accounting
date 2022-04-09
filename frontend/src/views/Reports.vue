<template>
    <Main>
        <h2>Отчеты</h2>
        <Reports class="wrapper" />
    </Main>
</template>

<script>
import Main from "../components/Main";
import Reports from "@/components/Reports/Reports.vue";

export default {
    props: {
        title: {
            type: String,
            require: true
        }
    },
    components: {
        Main,
        Reports
    },
    beforeMount() {
        document.title = this.title;
    }
};
</script>

<style scoped>
.wrapper {
    width: 1400px !important;
    margin: 0 auto;
}
</style>
