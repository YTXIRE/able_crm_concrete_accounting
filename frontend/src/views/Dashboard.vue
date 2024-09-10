<template>
    <Main>
        <h2>Dashboard</h2>
        <div class="wrapper">
            <MainChart />
        </div>
    </Main>
</template>

<script>
import Main from "../components/Main";
import MainChart from "@/components/Charts/MainChart.vue";

export default {
    props: {
        title: {
            type: String,
            require: true
        }
    },
    components: {
        MainChart,
        Main
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
