<template>
    <div>
        <el-button type="primary" @click="dialogVisible = true">
            <font-awesome-icon icon="edit" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Редактирование материала">
            <el-form ref="editMaterial" :model="fields" label-width="130px" @submit.prevent.stop>
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
        data: {
            require: true,
            type: Object,
        },
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
        ...mapActions(["updateMaterial"]),
        submitForm: function () {
            this.$refs["editMaterial"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.updateMaterial({
                        id: this.data.id,
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
    async mounted() {
        this.fields.name = this.data.name;
        this.fields.type_id = this.data.type_id;
    },
    watch: {
        data(e) {
            this.fields.name = e.name;
            this.fields.type_id = e.type_id;
        },
    },
};
</script>

<style scoped>
.el-select {
    width: 100%;
}
</style>
