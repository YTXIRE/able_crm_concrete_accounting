<template>
    <div>
        <el-form ref="formRef" :inline="true">
            <el-form-item label="Период">
                <el-select v-model="period" class="full" placeholder="Выберите период" size="large">
                    <el-option
                        v-for="item in periods"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                    >
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Временной диапозон">
                <el-date-picker
                    v-model="date_time"
                    :default-time="defaultTime"
                    class="full"
                    end-placeholder="Конец периода"
                    format="DD.MM.YYYY HH:mm:ss"
                    start-placeholder="Начало периода"
                    type="datetimerange"
                >
                </el-date-picker>
            </el-form-item>
            <el-form-item>
                <el-button :disabled="date_time?.length !== 2" type="primary" @click="clear">Сбросить</el-button>
            </el-form-item>
        </el-form>
        <vue3-chart-js ref="chartRef" v-bind="{ ...lineChart }" />
    </div>
</template>

<script>
import { ref } from "vue";
import Vue3ChartJs from "@j-t-mcc/vue3-chartjs";
import dataLabels from "chartjs-plugin-datalabels";
import { mapActions, mapGetters } from "vuex";

export default {
    components: {
        Vue3ChartJs
    },
    props: {
        data: {
            type: Object,
            require: true
        }
    },
    data() {
        return {
            defaultTime: [
                new Date(1970, 1, 1, 0, 0, 0),
                new Date()
            ],
            date_time: [],
            chartRef: ref(null),
            period: 30,
            periods: [
                {
                    label: "Все",
                    value: "all"
                },
                {
                    label: "1 день",
                    value: "1"
                },
                {
                    label: "2 дня",
                    value: "2"
                },
                {
                    label: "3 дня",
                    value: "3"
                },
                {
                    label: "4 дня",
                    value: "4"
                },
                {
                    label: "5 дней",
                    value: "5"
                },
                {
                    label: "6 дней",
                    value: "6"
                },
                {
                    label: "Неделя",
                    value: "7"
                },
                {
                    label: "2 недели",
                    value: "14"
                },
                {
                    label: "Месяц",
                    value: "30"
                },
                {
                    label: "2 месяца",
                    value: "60"
                },
                {
                    label: "3 месяца",
                    value: "90"
                },
                {
                    label: "4 месяца",
                    value: "120"
                },
                {
                    label: "5 месяцев",
                    value: "150"
                },
                {
                    label: "6 месяцев",
                    value: "180"
                },
                {
                    label: "7 месяцев",
                    value: "210"
                },
                {
                    label: "8 месяцев",
                    value: "240"
                },
                {
                    label: "9 месяцев",
                    value: "270"
                },
                {
                    label: "10 месяцев",
                    value: "300"
                },
                {
                    label: "11 месяцев",
                    value: "330"
                },
                {
                    label: "Год",
                    value: "365"
                }
            ],
            lineChart: {
                type: "line",
                plugins: [dataLabels],
                data: {
                    labels: this.data.labels,
                    datasets: this.data.operations
                },
                options: {
                    interaction: {
                        intersect: false
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: "Операции по дням"
                        },
                        datalabels: {
                            backgroundColor: function(context) {
                                return context.dataset.backgroundColor;
                            },
                            borderRadius: 4,
                            color: "white",
                            font: {
                                weight: "bold"
                            },
                            formatter: Math.round,
                            padding: 6
                        }
                    }
                }
            }
        };
    },
    methods: {
        ...mapActions(["setPeriod", "setDateFrom", "setDateTo", "setIsUpdatedDate"]),
        clear: function() {
            this.setDateFrom(0);
            this.setDateTo(Math.floor(new Date().getTime() / 1000));
            this.period = "30";
            this.date_time = [];
        }
    },
    computed: {
        ...mapGetters(["getPeriod", "getIsUpdated", "getIsUpdatedDate"])
    },
    mounted() {
        this.period = this.getPeriod;
    },
    watch: {
        period() {
            this.setPeriod(this.period);
        },
        date_time() {
            if (this.date_time?.length === 2) {
                this.setDateFrom(Math.floor(new Date(this.date_time[0]).getTime() / 1000));
                this.setDateTo(Math.floor(new Date(this.date_time[1]).getTime() / 1000));
                const old_period = this.period;
                this.period = "all";
                if (this.period === old_period && this.period === "all") {
                    this.setIsUpdatedDate(Math.random());
                }
            }
        },
        getIsUpdated() {
            this.lineChart.data.labels = this.data.labels;
            this.lineChart.data.datasets = this.data.operations;
            this.$refs.chartRef.update(250);
        }
    }
};
</script>
<style scoped>
.full {
    width: 100%;
}

.el-form-item:not(:last-child) {
    width: 611px;
}
</style>
