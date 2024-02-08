<template>
    <div>
        <el-button circle type="success" @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание нового юридического лица">
            <el-form ref="createLegalEntities" :model="fields" label-width="130px" @submit.prevent.stop>
                <el-form-item
                    :disabled="loading"
                    :rules="rules.legal_entities_type_id"
                    label="Тип управления"
                    prop="legal_entities_type_id"
                >
                    <el-select
                        v-model="fields.legal_entities_type_id"
                        no-data-text="Типов управления не найдено"
                        placeholder="Выберите тип управления"
                    >
                        <el-option
                            v-for="item in legal_entity_types"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        >
                            <span class="name">{{ item.full_name }}</span>
                            <span class="label">
                                {{ item.name }}
                            </span>
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="20" show-word-limit></el-input>
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button :disabled="loading" @click.prevent="dialogVisible = false">Отменить</el-button>
                    <el-button :disabled="loading" type="primary" @click.prevent="submitForm"> Сохранить </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    props: {
        legal_entity_types: {
            require: true,
            type: Array,
        },
    },
    data() {
        return {
            fields: {
                name: "",
                legal_entities_type_id: "",
            },
            options: [],
            loading: false,
            is_demo: false,
            dialogVisible: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите название организации",
                        trigger: "blur",
                    },
                ],
                legal_entities_type_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите тип управления",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["saveLegalEntities"]),
        submitForm: function () {
            this.$refs["createLegalEntities"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.saveLegalEntities({
                        name: this.fields.name,
                        legal_entities_type_id: this.fields.legal_entities_type_id,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.legal_entities_type_id = "";
                        this.fields.name = "";
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
    },
    mounted() {
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    }
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
</style>
