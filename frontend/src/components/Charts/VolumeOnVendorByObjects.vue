<template>
    <div>
        <vue3-chart-js v-bind="{ ...barChart }" />
    </div>
</template>

<script>
import Vue3ChartJs from "@j-t-mcc/vue3-chartjs";

export default {
    name: "App",
    components: {
        Vue3ChartJs,
    },
    props: {
        data: {
            type: Array,
            require: true,
        },
    },
    data() {
        return {
            barChart: {
                type: "bar",
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: "top",
                        },
                        title: {
                            display: true,
                            text: "Распределение материалов по поставщикам и объектам",
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function (value) {
                                    return `${value}`;
                                },
                            },
                        },
                    },
                },
                data: {
                    datasets: this.data,
                },
            },
        };
    },
};
</script>
