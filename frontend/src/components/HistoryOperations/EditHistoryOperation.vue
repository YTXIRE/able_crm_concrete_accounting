<template>
    <div>
        <el-button type="primary" @click.prevent.stop="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="edit" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Редактирование операции">
            <el-steps :active="active" align-center finish-status="success">
                <el-step title="Основа"></el-step>
                <el-step title="Детали"></el-step>
                <el-step title="Итог"></el-step>
            </el-steps>
            <br />
            <br />
            <el-form
                v-if="active === 0"
                ref="createLegalEntities"
                :model="fields"
                label-width="130px"
                @submit.prevent.stop
            >
                <el-form-item :disabled="loading" :rules="rules.vendor_id" label="Поставщик" prop="vendor_id">
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
                    :rules="rules.material_type_id"
                    label="Тип материала"
                    prop="material_type_id"
                >
                    <el-select
                        v-model="fields.material_type_id"
                        :disabled="!fields.vendor_id"
                        no-data-text="Типов материалов не найдено. Добавьте новый"
                        placeholder="Выберите тип материала"
                    >
                        <el-option v-for="item in materials_types" :key="item.id" :label="item.name" :value="item.id">
                            <span>{{ item.name }}</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.material_id" label="Материал" prop="material_id">
                    <el-select
                        v-model="fields.material_id"
                        :disabled="!fields.material_type_id"
                        no-data-text="Материалов не найдено. Добавьте новый"
                        placeholder="Выберите материал"
                        filterable
                    >
                        <el-option v-for="item in filteredMaterials" :key="item.id" :label="item.name" :value="item.id">
                            <span>{{ item.name }}</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.legal_entity_id" label="Платильщик" prop="legal_entity_id">
                    <el-select
                        v-model="fields.legal_entity_id"
                        :disabled="!fields.material_id"
                        no-data-text="Платильщик не найдено. Добавьте новый"
                        placeholder="Выберите платильщика"
                    >
                        <el-option v-for="item in legal_entities" :key="item.id" :label="item.full_name" :value="item.id">
                            <span>{{ item.full_name }}</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <div class="btn_footer">
                    <el-checkbox v-if="isDebtVisible" v-model="isDebt" label="Долг на конец года"></el-checkbox>
                    <el-button :disabled="this.active === 0" style="margin-top: 12px" @click="prev">Назад </el-button>
                    <el-button :disabled="!fields.legal_entity_id" style="margin-top: 12px" @click="next">Далее </el-button>
                </div>
            </el-form>
            <el-form
                v-if="active === 1"
                ref="createLegalEntities"
                :model="fields"
                label-width="130px"
                @submit.prevent.stop
            >
                <el-form-item :disabled="loading" :rules="rules.object_id" label="Объект" prop="object_id">
                    <el-select
                        v-model="fields.object_id"
                        :disabled="!fields.legal_entity_id"
                        no-data-text="Объектов не найдено. Добавьте новый или восстановите старый"
                        placeholder="Выберите объект"
                        filterable
                    >
                        <el-option v-for="item in objects" :key="item.id" :label="item.name" :value="item.id">
                            <span>{{ item.name }}</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :rules="rules.volume" label="Объем" prop="volume">
                    <el-input
                        v-model="fields.volume"
                        :disabled="!!!fields.object_id"
                        maxlength="12"
                        show-word-limit
                    ></el-input>
                </el-form-item>
                <el-form-item :rules="rules.price" label="Стоимость" prop="price">
                    <el-input
                        v-model="fields.price"
                        :disabled="!!!fields.volume"
                        maxlength="12"
                        show-word-limit
                    ></el-input>
                </el-form-item>
                <el-form-item :rules="rules.total" label="Итого" prop="total">
                    <el-input
                        v-model="fields.total"
                        :disabled="!!!fields.price"
                        maxlength="12"
                        show-word-limit
                    ></el-input>
                </el-form-item>
                <div class="btn_footer">
                    <el-button style="margin-top: 12px" @click="prev">Назад</el-button>
                    <el-button :disabled="!fields.total" style="margin-top: 12px" @click="next">Далее </el-button>
                </div>
            </el-form>
            <el-form
                v-if="active === 2"
                ref="createLegalEntities"
                :model="fields"
                label-width="130px"
                @submit.prevent.stop
            >
                <el-form-item :rules="rules.created_at" label="Дата операции" prop="created_at">
                    <el-date-picker
                        v-model="fields.created_at"
                        :disabled="!fields.total"
                        :shortcuts="shortcuts"
                        format="DD.MM.YYYY HH:mm:ss"
                        placeholder="Выберите дату и время"
                        type="datetime"
                    >
                    </el-date-picker>
                </el-form-item>
                <el-form-item :rules="rules.confirmed_data" label="Подтверждение" prop="confirmed_data">
                    <el-select
                        v-model="fields.confirmed_data"
                        :disabled="!fields.created_at"
                        placeholder="Выберите вариант подтверждения оперции"
                    >
                        <el-option label="Данные не подтверждены" value="0">
                            <span>Данные не подтверждены</span>
                        </el-option>
                        <el-option label="Данные подтверждены" value="1">
                            <span>Данные подтверждены</span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Комментарий" prop="total">
                    <el-input
                        v-model="fields.comment"
                        :disabled="!fields.confirmed_data"
                        maxlength="1000"
                        show-word-limit
                        type="textarea"
                    ></el-input>
                </el-form-item>
            </el-form>
            <template v-if="active === 2" #footer>
                <span class="dialog-footer">
                    <el-button :disabled="loading" style="margin-top: 12px" @click="prev">Назад</el-button>
                    <el-button
                        v-loading="loading"
                        :disabled="loading || !fields.confirmed_data"
                        type="primary"
                        @click.prevent="submitForm"
                    >
                        Сохранить
                    </el-button>
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
        materials: {
            require: true,
            type: Array,
        },
        materials_types: {
            require: true,
            type: Array,
        },
        objects: {
            require: true,
            type: Array,
        },
        legal_entities: {
            require: true,
            type: Array,
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
                material_id: "",
                legal_entity_id: "",
                object_id: "",
                volume: "",
                price: "",
                total: "",
                comment: "",
                created_at: new Date(),
                material_type_id: "",
                confirmed_data: "",
            },
            active: 0,
            options: [],
            loading: false,
            dialogVisible: false,
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
            rules: {
                vendor_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите поставщика",
                        trigger: "blur",
                    },
                ],
                material_type_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите тип материала",
                        trigger: "blur",
                    },
                ],
                legal_entity_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите платильщика",
                        trigger: "blur",
                    },
                ],
                material_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите материал",
                        trigger: "blur",
                    },
                ],
                object_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите объект",
                        trigger: "blur",
                    },
                ],
                volume: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите объем",
                        trigger: "blur",
                    },
                ],
                price: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите стоимость",
                        trigger: "blur",
                    },
                ],
                total: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите итоговую стоимость",
                        trigger: "blur",
                    },
                ],
                created_at: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите дату операции",
                        trigger: "blur",
                    },
                ],
                confirmed_data: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите подтверждение операции",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["updateHistoryOperation"]),
        submitForm: function () {
            this.$refs["createLegalEntities"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.updateHistoryOperation({
                        token: localStorage.getItem("crm_token"),
                        id: this.data.id,
                        vendor_id: this.fields.vendor_id,
                        material_id: this.fields.material_id,
                        legal_entity_id: this.fields.legal_entity_id,
                        object_id: this.fields.object_id,
                        volume: formatPrice(parseFloat(this.fields.volume.replace(",", "."))),
                        price: formatPrice(parseFloat(this.fields.price.replace(",", "."))),
                        total: formatPrice(parseFloat(this.fields.total.replace(",", "."))),
                        comment: this.fields.comment,
                        confirmed_data: Number(this.fields.confirmed_data),
                        created_at: Math.floor(new Date(this.fields.created_at).getTime() / 1000),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.vendor_id = "";
                        this.active = 0;
                        this.fields.material_id = "";
                        this.fields.material_type_id = "";
                        this.fields.legal_entity_id = "";
                        this.fields.object_id = "";
                        this.fields.volume = "";
                        this.fields.price = "";
                        this.fields.total = "";
                        this.fields.comment = "";
                        this.fields.confirmed_data = "";
                        this.fields.created_at = new Date();
                        this.dialogVisible = false;
                    } else {
                        this.loading = false;
                    }
                } else {
                    this.loading = false;
                    return false;
                }
            });
        },
        next() {
            if (this.active++ > 1) this.active = 0;
        },
        prev() {
            if (this.active-- < 1) this.active = 0;
        },
    },
    computed: {
        ...mapGetters(["getIsNewHistoryOperation"]),
        filteredMaterials() {
            if (this.materials?.length) {
                return this.materials.filter((material) => material.type_id === this.fields.material_type_id);
            }
            return this.materials;
        },
        material_type_id() {
            return this.fields.material_type_id;
        },
    },
    mounted() {
        this.fields.vendor_id = this.data.vendor.id;
        this.fields.material_id = this.data.material.id;
        this.fields.material_type_id = this.data.material.type_id;
        this.fields.legal_entity_id = this.data.legal_entity.id;
        this.fields.object_id = this.data.object.id;
        this.fields.volume = formatPrice(this.data.volume);
        this.fields.price = formatPrice(this.data.price);
        this.fields.total = formatPrice(this.data.total);
        this.fields.comment = this.data.comment;
        this.fields.confirmed_data = String(this.data.confirmed_data);
        this.fields.created_at = new Date(this.data.created_at * 1000);
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    watch: {
        material_type_id() {
            if (
                this.data.material.type_id !== this.fields.material_type_id ||
                this.fields.material_id !== this.data.material.id
            ) {
                this.fields.material_id = "";
            }
        },
        data(e) {
            this.fields.vendor_id = e.vendor.id;
            this.fields.material_id = e.material.id;
            this.fields.material_type_id = e.material.type_id;
            this.fields.legal_entity_id = e.legal_entity.id;
            this.fields.object_id = e.object.id;
            this.fields.confirmed_data = String(e.confirmed_data);
            this.fields.volume = formatPrice(e.volume);
            this.fields.price = formatPrice(e.price);
            this.fields.total = formatPrice(e.total);
            this.fields.comment = e.comment;
            this.fields.created_at = new Date(e.created_at * 1000);
        },
    },
};
</script>

<style scoped>
.el-select {
    width: 100%;
}

.el-select {
    width: 100%;
}

.name {
    float: right;
    color: var(--el-text-color-secondary);
    font-size: 13px;
}

.label {
    float: left;
}

.upload {
    display: grid !important;
}

.upload button {
    width: 100% !important;
    margin-top: 5px !important;
}
</style>
