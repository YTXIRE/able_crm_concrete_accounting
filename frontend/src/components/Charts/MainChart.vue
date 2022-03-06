<template>
    <div v-loading="loading">
        <div v-if="Object.keys(data.debts).length">
            <div>
                <el-row v-if="Object.keys(data.debts).length" :gutter="24">
                    <el-col :span="24">
                        <el-card shadow="hover">
                            <p><b>Долги</b></p>
                            <el-table :data="Object.keys(data.debts)" border style="width: 100%">
                                <el-table-column label="Поставщик">
                                    <template #default="scope">
                                        {{ scope.row }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="Взяли">
                                    <template #default="scope">
                                        <table v-for="item in Object.keys(data.debts[scope.row]['Взяли'])"
                                               :key="item"
                                               class="debt_data"
                                        >
                                            <tr>
                                                <td class="data_text">
                                                    {{ item }}: <span
                                                    class="green">{{ format_price(data.debts[scope.row]["Взяли"][item])
                                                    }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Долг">
                                    <template #default="scope">
                                        <table v-for="item in Object.keys(data.debts[scope.row]['Долг'])"
                                               :key="item"
                                               class="debt_data"
                                        >
                                            <tr>
                                                <td class="data_text">
                                                    {{ item }}:
                                                    <span :class="[
                                                    data.debts[scope.row]['Долг'][item] > 0
                                                        ? 'red'
                                                        : data.debts[scope.row]['Долг'][item] < 0
                                                        ? 'blue'
                                                        : '',
                                                ]">
                                                        {{ format_price(data.debts[scope.row]["Долг"][item]) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-card>
                    </el-col>
                </el-row>
                <div v-else>
                    <el-card shadow="hover">
                        <div>Данных по долгам нет</div>
                    </el-card>
                </div>
            </div>
            <br />
            <div>
                <el-row v-if="Object.keys(operationsByMonths).length" :gutter="24">
                    <el-col :span="24">
                        <el-card shadow="hover">
                            <OperationsWithDays v-if="loaded" :data="operationsByMonths" />
                        </el-card>
                    </el-col>
                </el-row>
                <div v-else>
                    <el-card shadow="hover">
                        <div>Данных по операция нет</div>
                    </el-card>
                </div>
            </div>
            <br />
            <div>
                <el-row v-if="Object.keys(amountMaterialsOnObject).length" :gutter="24">
                    <el-col :span="18">
                        <el-card shadow="hover">
                            <AmountMaterialsOnObject v-if="loaded" :data="amountMaterialsOnObject" />
                        </el-card>
                    </el-col>
                    <el-col :span="6">
                        <el-card class="max_h_amount" shadow="hover">
                            <table
                                class="debt_data"
                            >
                                <tr
                                    v-for="material in Object.keys(data.amount_materials_on_object.operations)"
                                    :key="material"
                                    class="table_amount_material"
                                >
                                    <td class="headers_table_debt">{{ material }}</td>
                                    <td class="green">
                                        {{ format_volume(data.amount_materials_on_object.operations[material]) }}
                                        {{
                                            volume_format(
                                                data.amount_materials_on_object.labels[material].toLowerCase()
                                            )
                                        }}
                                    </td>
                                </tr>
                            </table>
                        </el-card>
                    </el-col>
                </el-row>
                <div v-else>
                    <el-card shadow="hover">
                        <div>Данных по объектам нет</div>
                    </el-card>
                </div>
            </div>
            <br />
            <div>
                <el-row v-if="Object.keys(volumeOnVendorByObjects).length" :gutter="24">
                    <el-col :span="24">
                        <el-card shadow="hover">
                            <VolumeOnVendorByObjects v-if="loaded" :data="volumeOnVendorByObjects" />
                        </el-card>
                    </el-col>
                </el-row>
                <div v-else>
                    <el-card shadow="hover">
                        <div>Данных по операция нет</div>
                    </el-card>
                </div>
            </div>
        </div>
        <div v-else>
            <el-card shadow="hover">
                <div>Нет данных</div>
            </el-card>
        </div>
    </div>
</template>

<script>
import OperationsWithDays from "@/components/Charts/OperationsWithDays.vue";
import AmountMaterialsOnObject from "@/components/Charts/AmountMaterialsOnObject.vue";
import VolumeOnVendorByObjects from "@/components/Charts/VolumeOnVendorByObjects.vue";

import { mapActions, mapGetters } from "vuex";
import { formatNumber, formatVolume, volume } from "@/utils/helper.js";

export default {
    components: {
        OperationsWithDays,
        AmountMaterialsOnObject,
        VolumeOnVendorByObjects
    },
    data() {
        return {
            data: {
                debts: []
            },
            chartDebt: [],
            operationsByMonths: {
                operations: [],
                labels: []
            },
            amountMaterialsOnObject: [],
            volumeOnVendorByObjects: [],
            colors: {},
            loaded: false,
            loading: false
        };
    },
    methods: {
        ...mapActions(["getDataDashboard", "setIsUpdated"]),
        randomColor: () => {
            return "#" + (Math.random().toString(16) + "000000").substring(2, 8).toUpperCase();
        },
        format_price: function(price) {
            return formatNumber(price);
        },
        format_volume: function(data) {
            return formatVolume(data);
        },
        volume_format: function(data) {
            return volume(data);
        },
        get_data_dashboard: async function() {
            this.loading = true;
            this.data = await this.getDataDashboard({
                token: localStorage.getItem("crm_token"),
                period: this.getPeriod,
                date_from: this.getDateFrom,
                date_to: this.getDateTo
            });
            for (let item in this.data.debts) {
                this.colors[item] = this.randomColor(Object.keys(this.data.debts).length);
            }
            for (let item in this.data.debts) {
                this.chartDebt.push({
                    label: item,
                    backgroundColor: this.colors[item],
                    data: this.data.debts[item]
                });
            }
            let operations = [];
            for (let item in this.data.operations_by_months.operations) {
                if (this.data.operations_by_months.operations[item].reduce(function(sum, elem) {
                    return sum + elem;
                }, 0) > 0) {
                    operations.push({
                        label: item,
                        backgroundColor: this.colors[item],
                        borderColor: this.colors[item],
                        data: this.data.operations_by_months.operations[item].map((item) => (item === 0 ? NaN : item)),
                        fill: false,
                        spanGaps: true
                    });
                }
            }
            this.operationsByMonths.operations = operations;
            this.operationsByMonths.labels = this.data.operations_by_months.labels;
            for (let item in this.data.amount_materials_on_object.operations) {
                this.amountMaterialsOnObject.push({
                    label: item,
                    backgroundColor: this.randomColor(),
                    data: {
                        [item]: this.data.amount_materials_on_object.operations[item]
                    }
                });
            }
            for (let item in this.data.volume_on_vendor_by_objects) {
                this.volumeOnVendorByObjects.push({
                    label: item,
                    backgroundColor: this.colors[item],
                    data: this.data.volume_on_vendor_by_objects[item]
                });
            }
            this.setIsUpdated(Math.random());
            this.loaded = true;
            this.loading = false;
        }
    },
    async mounted() {
        await this.get_data_dashboard();
    },
    computed: {
        ...mapGetters(["getPeriod", "getDateFrom", "getDateTo", "getIsUpdatedDate"])
    },
    watch: {
        async getPeriod() {
            await this.get_data_dashboard();
        },
        async getIsUpdatedDate() {
            await this.get_data_dashboard();
        }
    }
};
</script>

<style scoped>
.debt_data {
    text-align: left !important;
}

.headers_table_debt {
    font-weight: bold;
    width: 100px;
}

.green {
    color: green;
    font-weight: 700;
}

.red {
    color: red;
    font-weight: 700;
}

.blue {
    color: #2b2bde;
    font-weight: 700;
}

.debt_graph_height {
    height: 540px;
}

.table_amount_material {
    display: block !important;
    padding-bottom: 10px !important;
    border-bottom: 1px solid #ccc;
    width: 115%;
    padding-top: 10px;
}

.max_h_amount {
    max-height: 540px !important;
    overflow: auto !important;
}
</style>
