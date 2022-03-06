<template>
    <div class="wrapper">
        <CreatePayment
            :legal_entities="edit.legal_entities.legal_entities"
            :vendors="edit.vendors.vendors"
            :material_types="edit.material_types.types"
            class="create"
        />
        <el-row v-if="Object.keys(legal_entity_ids).length" :gutter="24">
            <el-col :span="5">
                <el-card v-loading="loadingMenu" shadow="never">
                    <el-menu>
                        <el-menu-item
                            v-for="key in Object.keys(legal_entity_ids)"
                            :key="legal_entity_ids[key]"
                            :index="`${key}_!-#$te__${legal_entity_ids[key]}`"
                            @click="set_vendor"
                        >
                            <span class="text">{{ key }}</span>
                        </el-menu-item>
                    </el-menu>
                </el-card>
            </el-col>
            <el-col :span="19">
                <el-card shadow="never">
                    <div v-if="current_vendor">
                        <el-table
                            v-loading="loading"
                            :data="payments[current_vendor]"
                            :default-sort="{ prop: 'id', order: 'descending' }"
                            empty-text="Данных нет"
                            style="width: 100%"
                        >
                            <el-table-column label="ID" prop="id" sortable width="80" />
                            <el-table-column label="Иконка" width="120">
                                <template #default="scope" class="wrapper_vendor">
                                    <font-awesome-icon :icon="[scope.row.prefix, scope.row.icon]"
                                                       class="icon size_icon" />
                                </template>
                            </el-table-column>
                            <el-table-column label="Поставщик" prop="vendor" sortable width="200" />
                            <el-table-column label="Тип материала" prop="material_type" sortable width="200">
                            </el-table-column>
                            <el-table-column :sort-method="sortAmount" label="Сумма" sortable>
                                <template #default="scope" class="wrapper_vendor">
                                    {{ format_amount(scope.row.amount) }}
                                </template>
                            </el-table-column>
                            <el-table-column :sort-method="sortDate" label="Дата оплаты" sortable>
                                <template #default="scope" class="wrapper_vendor">
                                    {{ format_date(scope.row.created_at) }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Действие" width="145">
                                <template #default="scope">
                                    <EditPayment
                                        :data="scope.row"
                                        :legal_entities="edit.legal_entities.legal_entities"
                                        :vendors="edit.vendors.vendors"
                                        :material_types="edit.material_types.types"
                                        class="block"
                                    />
                                    <el-popconfirm
                                        cancel-button-text="Стой! Оставь"
                                        confirm-button-text="Естественно"
                                        icon-color="red"
                                        title="Вы точно хотите удалить?"
                                        @confirm="remove(scope.row.id)"
                                    >
                                        <template #reference>
                                            <el-button class="remove" type="danger">
                                                <font-awesome-icon icon="trash" />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </template>
                            </el-table-column>
                        </el-table>
                        <br />
                        <el-pagination
                            :current-page="pagination[current_vendor]"
                            :page-size="10"
                            :total="Number(count[current_vendor])"
                            background
                            class="pagination"
                            hide-on-single-page
                            layout="prev, pager, next"
                            @next-click="get_payments"
                            @prev-click="get_payments"
                            @current-change="get_payments"
                        ></el-pagination>
                    </div>
                    <div v-else>Юридическое лицо не выбран</div>
                </el-card>
            </el-col>
        </el-row>
        <el-card v-else v-loading="loadingMenu">
            <div>Нет данных</div>
        </el-card>
    </div>
</template>

<script>
import CreatePayment from "./CreatePayment";
import EditPayment from "./EditPayment";
import { formatDate, formatNumber } from "@/utils/helper.js";

import { mapActions, mapGetters } from "vuex";

export default {
    components: {
        CreatePayment,
        EditPayment
    },
    data() {
        return {
            loading: false,
            loadingMenu: false,
            payments: [],
            count: [],
            legal_entity_ids: [],
            current_tab: 1,
            offsets: [],
            current_vendor: "",
            pagination: {},
            edit: {
                vendors: [],
                legal_entities: [],
                material_types: []
            }
        };
    },
    methods: {
        ...mapActions(["getAllPayments", "setLegalEntityId", "getAllVendors", "getAllLegalEntities", "deletePayment",
            "getAllMaterialTypes"]),
        format_amount: function(amount) {
            return formatNumber(amount);
        },
        format_date: function(date) {
            return formatDate(date);
        },
        get_payments: function(e) {
            this.pagination[this.current_vendor] = e;
            this.get_data(e * 10 - 10, this.current_tab);
        },
        async get_data(offset, legal_entity_id, is_update = false) {
            this.loading = true;
            if (!is_update) {
                this.offsets[legal_entity_id] = offset;
            }
            const { payments, count, legal_entity_ids } = await this.getAllPayments({
                token: localStorage.getItem("crm_token"),
                offset: Object.keys(this.offsets).length ? this.offsets : { 0: 0 }
            });
            if (Object.keys(this.payments).length !== Object.keys(payments).length) {
                this.current_vendor = "";
            }
            this.payments = payments;
            this.count = count;
            this.legal_entity_ids = legal_entity_ids;
            this.loading = false;
        },
        set_vendor: function(e) {
            const index = e.index.split("_!-#$te__");
            this.current_tab = index[1];
            this.current_vendor = index[0];
            this.setLegalEntityId(Number(index[1]));
        },
        sortAmount: (a, b) => {
            return a.amount - b.amount;
        },
        sortDate: (a, b) => {
            return a.created_at - b.created_at;
        },
        remove: async function(id) {
            if (this.payments[this.current_vendor]?.length < 2) {
                this.offsets[this.current_tab] = 0;
                this.pagination[this.current_vendor] = 1;
            }
            await this.deletePayment({
                id,
                token: localStorage.getItem("crm_token")
            });
        }
    },
    async mounted() {
        this.loadingMenu = true;
        await this.get_data(0, this.current_tab);
        for (let item in this.legal_entity_ids) {
            this.offsets[this.legal_entity_ids[item]] = 0;
            this.pagination[item] = 1;
        }
        this.loadingMenu = false;
        this.edit.vendors = await this.getAllVendors({
            token: localStorage.getItem("crm_token")
        });
        this.edit.legal_entities = await this.getAllLegalEntities({
            token: localStorage.getItem("crm_token")
        });
        this.edit.material_types = await this.getAllMaterialTypes({
            token: localStorage.getItem("crm_token")
        });
        this.setLegalEntityId(0);
    },
    computed: {
        ...mapGetters(["getIsNewPayment"])
    },
    watch: {
        getIsNewPayment: async function() {
            await this.get_data(0, this.current_tab, true);
        }
    }
};
</script>

<style scoped>
.wrapper {
    position: relative;
    margin: auto;
    width: 1400px !important;
}

.create {
    position: absolute;
    right: -50px;
}

.icon {
    font-size: 30px;
}

.tabs {
    min-height: 50px;
}

.small {
    font-size: 15px;
}

.el-menu {
    border: none;
    text-align: left;
}

.block {
    display: inline;
}

.remove {
    margin-left: 10px;
}

.size_icon {
    margin-top: 3px;
}
</style>
