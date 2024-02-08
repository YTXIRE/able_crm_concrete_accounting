<template>
    <div>
        <el-button circle type="success" @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание материала">
            <el-form ref="createMaterial" :model="fields" label-width="130px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="100" show-word-limit></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.type_id" label="Тип материала" prop="type_id">
                    <el-select
                        v-model="fields.type_id"
                        no-data-text="Типы материалов не найдены. Добавьте новый"
                        placeholder="Выберите тип материала"
                    >
                        <el-option v-for="item in material_types" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
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
        material_types: {
            require: true,
            type: Array,
        },
    },
    data() {
        return {
            fields: {
                name: "",
                type_id: "",
            },
            options: [],
            loading: false,
            is_demo: false,
            dialogVisible: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите название типа материала",
                        trigger: "blur",
                    },
                ],
                type_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите типа материала",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["saveMaterial", "getAllMaterialTypes"]),
        submitForm: function () {
            this.$refs["createMaterial"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.saveMaterial({
                        name: this.fields.name,
                        type_id: this.fields.type_id,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.type_id = "";
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
</style>
