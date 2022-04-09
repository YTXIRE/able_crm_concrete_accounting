<template>
    <el-button @click="dialogVisible = true">Добавить новый фильтр</el-button>
    <el-dialog v-model="dialogVisible" fullscreen title="Настройка представления">
        <div class="main_block">
            <el-input
                v-model="filter_name"
                :validate-event="true"
                maxlength="100"
                placeholder="Введите имя фильтра"
                show-word-limit
            />
            <el-divider />
            <div v-if="!!filters_block" id="container">
                <div class="wrapper">
                    <div
                        v-for="block in filters_block"
                        :key="block.id"
                        :class="block.position === 'first' ? 'mt-block' : ''"
                        class="wrapper"
                    >
                        <div v-if="block.position !== 'first'">
                            <el-select v-model="block.unity" class="unity">
                                <el-option
                                    v-for="item in unity"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value"
                                >
                                </el-option>
                            </el-select>
                        </div>
                        <el-card class="card" shadow="hover">
                            <el-button
                                :disabled="filters_block.length === 1"
                                class="remove"
                                plain
                                type="danger"
                                @click="remove_filters_block(block.id)"
                            >
                                <font-awesome-icon icon="trash-restore" />
                            </el-button>
                            <div class="card_block">
                                <el-select v-model="block.field" placeholder="Выберите поле">
                                    <el-option
                                        v-for="item in filtered_fields"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value"
                                    >
                                    </el-option>
                                </el-select>
                                <br />
                                <br />
                                <el-select v-model="block.operation" placeholder="Выберите действие">
                                    <el-option
                                        v-for="item in operations"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value"
                                    >
                                    </el-option>
                                </el-select>
                                <br />
                                <br />
                                <el-select
                                    v-if="check_field_type(block.field) === 'selector' && block.field === 'legal_entity'"
                                    v-model="block.value"
                                    :placeholder="label_filter"
                                >
                                    <el-option
                                        v-for="item in filters_data[block.field]"
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
                                <el-select
                                    v-else-if="check_field_type(block.field) === 'selector' && block.field !== 'legal_entity'"
                                    v-model="block.value"
                                    :placeholder="label_filter"
                                >
                                    <el-option
                                        v-for="item in filters_data[block.field]"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    >
                                    </el-option>
                                </el-select>
                                <el-date-picker
                                    v-else-if="check_field_type(block.field) === 'datepicker'"
                                    v-model="block.value"
                                    :shortcuts="shortcuts"
                                    format="DD.MM.YYYY HH:mm:ss"
                                    placeholder="Выберите дату и время"
                                    type="datetime"
                                >
                                </el-date-picker>
                                <el-input v-else v-model="block.value" placeholder="Значение" />
                            </div>
                        </el-card>
                    </div>
                    <el-button circle class="add" type="success" @click="add">
                        <font-awesome-icon icon="plus" />
                    </el-button>
                </div>
            </div>
        </div>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">Отмена</el-button>
                <el-button type="primary" @click="createFilter"> Сохранить </el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script>
import { mapActions } from "vuex";
import _ from "lodash";
import { ElMessage } from "element-plus";
import { formatPrice } from "@/utils/helper.js";

export default {
    props: {
        filters_data_new: {
            require: true,
            type: Object
        }
    },
    data() {
        return {
            isReady: false,
            dialogVisible: false,
            filter_name: "",
            unity: [
                {
                    label: "И",
                    value: "and"
                },
                {
                    label: "ИЛИ",
                    value: "or"
                }
            ],
            filters_block: [
                {
                    id: Math.random(),
                    field: "",
                    operation: "",
                    value: "",
                    position: "first",
                    unity: "and"
                }
            ],
            old_filters_block: [],
            filters_data: {
                confirm_history_operation: []
            },
            label_filter: "",
            shortcuts: [
                {
                    text: "Сегодня",
                    value: new Date()
                },
                {
                    text: "Вчера",
                    value: () => {
                        const date = new Date();
                        date.setTime(date.getTime() - 3600 * 1000 * 24);
                        return date;
                    }
                }
            ],
            filtered_fields: [
                {
                    label: "Поставщик",
                    value: "vendor"
                },
                {
                    label: "Объект",
                    value: "object"
                },
                {
                    label: "Юридическое лицо",
                    value: "legal_entity"
                },
                {
                    label: "Материал",
                    value: "material"
                },
                {
                    label: "Тип материала",
                    value: "material_type"
                },
                {
                    label: "Стоимость",
                    value: "price"
                },
                {
                    label: "Итоговая стоимость",
                    value: "total"
                },
                {
                    label: "Объем",
                    value: "volume"
                },
                {
                    label: "Дата от",
                    value: "date_from"
                },
                {
                    label: "Дата до",
                    value: "date_to"
                },
                {
                    label: "Подтвержденные операции",
                    value: "confirm_history_operation"
                }
            ],
            operations: [
                {
                    value: "equal",
                    label: "Равно"
                },
                {
                    value: "not_equal",
                    label: "Не равно"
                },
                {
                    value: "over",
                    label: "Больше"
                },
                {
                    value: "over_or_equal",
                    label: "Больше или равно"
                },
                {
                    value: "less",
                    label: "Меньше"
                },
                {
                    value: "less_or_equal",
                    label: "Меньше или равно"
                }
            ],
            confirmation_data: [
                {
                    name: "Данные подтверждены",
                    id: 1
                },
                {
                    name: "Данные не подтверждены",
                    id: 0
                }
            ]
        };
    },
    methods: {
        ...mapActions([
            "getAllVendors",
            "getAllObjects",
            "getAllLegalEntities",
            "getAllMaterials",
            "getAllMaterialTypes",
            "saveFilter"
        ]),
        add() {
            this.filters_block.push({
                id: Math.random(),
                field: "",
                operation: "",
                value: "",
                position: "",
                unity: "and"
            });
        },
        remove_filters_block(id) {
            for (let i in this.filters_block) {
                if (this.filters_block[i].id === id && this.filters_block[i].position === "first") {
                    this.filters_block[i].position = "removed";
                    if (this.filters_block[Number(i) + 1]?.id) {
                        this.filters_block[Number(i) + 1].position = "first";
                    }
                }
            }
            this.filters_block = this.filters_block.filter((block) => block.id !== id);
        },
        check_field_type(field) {
            if (
                ["vendor", "object", "legal_entity", "material", "material_type", "confirm_history_operation"].includes(
                    field
                )
            ) {
                return "selector";
            }
            if (["price", "total", "volume"].includes(field)) {
                return "input";
            }
            if (["date_from", "date_to"].includes(field)) {
                return "datepicker";
            }
        },
        setValue: function() {
            this.old_filters_block = _.cloneDeep(this.filters_block);
        },
        createFilter: async function() {
            let errors = [];
            if (this.filter_name === "") {
                errors.push("label");
            } else {
                errors = errors.filter((err) => err !== "label");
            }
            this.filters_block.map((item) => {
                if (item.field === "" || item.value === "" || item.operation === "") {
                    errors.push(item.id);
                } else {
                    errors = errors.filter((err) => err !== item.id);
                }
            });
            if (errors.length === 0) {
                if (this.filters_block.length !== 0) {
                    const fb = { ...this.filters_block };
                    for (let item in fb) {
                        if (String(fb[item]["field"]).startsWith("date_")) {
                            fb[item]["value"] = Math.floor(new Date(fb[item]["value"]).getTime() / 1000);
                        }
                        if (["price", "total", "volume"].includes(fb[item]["field"])) {
                            fb[item]["value"] = formatPrice(parseFloat(fb[item]["value"].replace(",", ".")));
                        }
                    }
                    const response = await this.saveFilter({
                        token: localStorage.getItem("crm_token"),
                        name: this.filter_name,
                        filters: fb
                    });
                    if (response) {
                        this.dialogVisible = false;
                        this.filter_name = "";
                        this.filters_block = [
                            {
                                id: Math.random(),
                                field: "",
                                operation: "",
                                value: "",
                                position: "first",
                                unity: "and"
                            }
                        ];
                        this.old_filters_block = [];
                    }
                }
            } else {
                ElMessage.error("Пожалуйста, заполните все поля");
            }
        }
    },
    mounted() {
        this.setValue();
        this.filters_data = {
            ...this.filters_data,
            ...this.filters_data_new
        };
    },
    watch: {
        filters_block: {
            async handler(after) {
                const vm = this;
                let changed = after.filter(function(p, idx) {
                    return Object.keys(p).some(function(prop) {
                        return p[prop] !== vm.old_filters_block?.[idx]?.[prop];
                    });
                })[0];
                if (changed?.field === "vendor") {
                    this.label_filter = "Выберите поставщика";
                }
                if (changed?.field === "object") {
                    this.label_filter = "Выберите объект";
                }
                if (changed?.field === "legal_entity") {
                    this.label_filter = "Выберите юридическое лицо";
                }
                if (changed?.field === "material") {
                    this.label_filter = "Выберите материал";
                }
                if (changed?.field === "material_type") {
                    this.label_filter = "Выберите тип материала";
                }
                if (
                    changed?.field === "confirm_history_operation" &&
                    this.filters_data.confirm_history_operation.length === 0
                ) {
                    this.filters_data.confirm_history_operation = this.confirmation_data;
                    this.label_filter = "Выберите подтверждение";
                }
                vm.setValue();
            },
            deep: true
        }
    }
};
</script>
<style scoped>
.main_block {
    min-height: calc(100vh - 150px);
}

.el-select {
    width: 100% !important;
}

.and {
    font-size: 30px;
    display: block;
    padding-top: 85px;
    margin-left: 20px;
    margin-right: 20px;
}

.add {
    display: block;
    margin-top: 80px;
    margin-left: 10px;
    width: 40px;
    height: 40px;
}

.card {
    margin-bottom: 10px;
    width: 361px;
    position: relative;
}

.remove {
    position: absolute;
    top: 20px;
    right: 20px;
}

#container,
.wrapper {
    display: flex;
    flex-wrap: wrap;
}

.mt-block {
    margin-left: 25px;
}

.card_block {
    width: 260px;
}

.red {
    outline: 1px solid red;
    border-radius: 2pt;
}

.unity {
    max-width: 85px !important;
    font-size: 30px;
    display: block;
    padding-top: 78px;
    margin-left: 20px;
    margin-right: 20px;
}
</style>
