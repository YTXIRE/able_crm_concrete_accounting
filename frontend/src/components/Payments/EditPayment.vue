<template>
    <div>
        <el-button type="primary" @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="edit" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Редактирование платежа">
            <el-form ref="updatePayment" :model="fields" label-width="170px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.vendor_id" label="Постащик" prop="vendor_id">
                    <el-select
                        v-model="fields.vendor_id"
                        no-data-text="Поставщиков не найдено. Добавьте нового или восстановите старого"
                        placeholder="Выберите поставщика"
                    >
                        <el-option v-for="item in vendors" :key="item.id" :label="item.name" :value="item.id">
                            <span>
                                <font-awesome-icon :icon="[item.prefix, item.icon]" class="icon" />&nbsp;
                                {{ item.name }}
                            </span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item
                    :disabled="loading"
                    :rules="rules.legal_entity_id"
                    label="Юридическое лицо"
                    prop="legal_entity_id"
                >
                    <el-select
                        v-model="fields.legal_entity_id"
                        no-data-text="Юридических лиц не найдено. Добавьте новое или восстановите старое"
                        placeholder="Выберите юридическое лицо"
                    >

                        <el-option
                            v-for="item in legal_entities"
                            :key="item.id"
                            :label="`${item.legal_entities_type} ${item.name}`"
                            :value="item.id"
                        >
                            <span>
                                {{ item.legal_entities_type }}
                                {{ item.name }}
                            </span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item
                    :disabled="loading"
                    :rules="rules.material_type_id"
                    label="Тип материала"
                    prop="material_type_id"
                >
                    <el-select
                        v-model="fields.material_type_id"
                        no-data-text="Тип материала не найден не найдено. Добавьте новое"
                        placeholder="Выберите тип материала"
                    >
                        <el-option
                            v-for="item in material_types"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        >
                            {{ item.name }}
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.amount" label="Сумма" prop="amount">
                    <el-input
                        v-model="fields.amount"
                        maxlength="15"
                        show-word-limit
                        type="number"
                        @blur="format_price"
                    ></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.created_at" label="Дата платежа" prop="created_at">
                    <el-date-picker
                        v-model="fields.created_at"
                        :shortcuts="shortcuts"
                        format="DD.MM.YYYY HH:mm:ss"
                        placeholder="Выберите дату и время"
                        type="datetime"
                    >
                    </el-date-picker>
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-checkbox v-model="isRefund" label="Возврат"></el-checkbox>
                    <el-button @click.prevent="dialogVisible = false">Отменить</el-button>
                    <el-button :disabled="loading" type="primary" @click.prevent="submitForm"> Сохранить </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { formatPrice } from "@/utils/helper.js";

export default {
    props: {
        vendors: {
            require: true,
            type: Array,
        },
        legal_entities: {
            require: true,
            type: Array,
        },
        material_types: {
            require: true,
            type: Array
        },
        data: {
            require: true,
            type: Object,
        },
    },
    data() {
        return {
            fields: {
                vendor_id: "",
                legal_entity_id: "",
                amount: null,
                created_at: "",
                operation_type: "",
            },
            isRefund: false,
            is_demo: false,
            shortcuts: [
                {
                    text: "Сегодня",
                    value: new Date(),
                },
                {
                    text: "Вчера",
                    value: () => {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        return date;
                    },
                },
            ],
            loading: false,
            dialogVisible: false,
            rules: {
                vendor_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите поставщика",
                        trigger: "blur",
                    },
                ],
                legal_entity_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите юридическое лицо",
                        trigger: "blur",
                    },
                ],
                material_type_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите тип материала",
                        trigger: "blur"
                    }
                ],
                amount: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите сумму",
                        trigger: "blur",
                    },
                ],
                created_at: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите дату платежа",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["updatePayment"]),
        submitForm: function () {
            this.$refs["updatePayment"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    if (!isNaN(parseFloat(this.fields.amount)) && !isFinite(this.fields.amount)) {
                        this.fields.amount = this.fields.amount.replace(",", ".")
                    }
                    const result = await this.updatePayment({
                        id: this.data.id,
                        vendor_id: this.fields.vendor_id,
                        legal_entity_id: this.fields.legal_entity_id,
                        material_type_id: this.fields.material_type_id,
                        operation_type: this.isRefund ? 'refund' : 'buy',
                        amount: this.isRefund ? -this.fields.amount : this.fields.amount,
                        created_at: Math.floor(new Date(this.fields.created_at).getTime() / 1000),
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.vendor_id = "";
                        this.fields.legal_entity_id = "";
                        this.fields.material_type_id = "";
                        this.fields.amount = null;
                        this.fields.created_at = new Date();
                        this.dialogVisible = false;
                        this.isRefund = false;
                    } else {
                        this.loading = false;
                    }
                } else {
                    this.loading = false;
                    return false;
                }
            });
        },
        format_price: function (e) {
            this.fields.amount = formatPrice(parseFloat(e.target.value));
        },
    },
    mounted() {
        const check_vendors = (vendor_id) => {
            let result = "";
            if (this.vendors.length) {
                this.vendors.map((vendor) => {
                    if (vendor.id === vendor_id) {
                        result = vendor_id;
                    }
                });
            }
            return result ?? "";
        };
        const check_material_types = (id) => {
            let result = "";
            if (this.material_types?.length) {
                this.material_types.map((material_type) => {
                    if (material_type.id === id) {
                        result = id;
                    }
                });
            }
            return result ?? "";
        };
        const check_legal_entity = (id) => {
            let result = "";
            if (this.legal_entities?.length) {
                this.legal_entities.map((legal_entity) => {
                    if (legal_entity.id === id) {
                        result = id;
                    }
                });
            }
            return result ?? "";
        };
        this.fields.vendor_id = check_vendors(this.data.vendor_id);
        this.fields.legal_entity_id = check_legal_entity(this.getLegalEntityId);
        this.fields.material_type_id = check_material_types(this.data.material_type_id);
        this.fields.amount = this.data.amount < 0 ? -this.data.amount : this.data.amount;
        this.fields.operation_type = this.data.operation_type;
        this.fields.created_at = new Date(this.data.created_at * 1000);
        this.isRefund = this.data.operation_type === 'refund'
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    computed: {
        ...mapGetters(["getLegalEntityId"]),
    },
    watch: {
        getLegalEntityId: function () {
            this.fields.legal_entity_id = this.getLegalEntityId;
        },
        data(e) {
            this.fields.vendor_id = e.vendor_id;
            this.fields.legal_entity_id = e.legal_entity_id;
            this.fields.material_type_id = e.material_type_id;
            this.fields.amount = e.amount < 0 ? -e.amount : e.amount;
            this.fields.operation_type = e.operation_type;
            this.fields.created_at = e.created_at * 1000;
            this.isRefund = e.operation_type === 'refund'
        },
    },
};
</script>

<style scoped>
.el-select {
    width: 100% !important;
}

.el-checkbox {
    position: absolute;
    left: 180px;
    bottom: 30px;
}
</style>
