<template>
    <div>
        <el-button circle type="success" @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание объекта">
            <el-form ref="createObject" :model="fields" label-width="120px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="100" show-word-limit></el-input>
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
    data() {
        return {
            fields: {
                name: "",
            },
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
            },
        };
    },
    methods: {
        ...mapActions(["saveObject"]),
        submitForm: function () {
            this.$refs["createObject"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.saveObject({
                        name: this.fields.name,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.name = "";
                        this.dialogVisible = false;
                    } else {
                        this.loading = false;
                    }
                } else {
                    this.loading = false;
                    this.fields.name = "";
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
