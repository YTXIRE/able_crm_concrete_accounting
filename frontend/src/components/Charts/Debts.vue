<template>
    <div>
        <vue3-chart-js v-bind="{ ...barChart }" />
    </div>
</template>

<script>
import Vue3ChartJs from "@j-t-mcc/vue3-chartjs";
import zoomPlugin from "chartjs-plugin-zoom";

Vue3ChartJs.registerGlobalPlugins([zoomPlugin]);

export default {
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
                            text: "Взяли/Долг",
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true,
                                },
                                mode: "y",
                            },
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
