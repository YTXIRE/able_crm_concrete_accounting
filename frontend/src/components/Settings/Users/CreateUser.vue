<template>
    <div>
        <el-button circle class="add_user" type="success" @click="dialogVisible = true">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание пользователя">
            <el-form ref="createUser" :model="fields" label-width="80px">
                <el-form-item :disabled="loading" :rules="rules.login" label="Логин" prop="login">
                    <el-input v-model="fields.login" maxlength="100" show-word-limit></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.email" label="Email" prop="email">
                    <el-input v-model="fields.email" maxlength="100" show-word-limit></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.password" label="Пароль" prop="password">
                    <el-input v-model="fields.password" maxlength="100" show-word-limit type="password"></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.is_demo" label="Демо" prop="is_demo">
                    <el-checkbox v-model="fields.is_demo" :label="fields.is_demo ? 'Да' : 'Нет'" class="is_demo"></el-checkbox>
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button :disabled="loading" @click="dialogVisible = false">Отменить</el-button>
                    <el-button :disabled="loading" type="primary" @click="submitForm"> Сохранить </el-button>
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
                login: "",
                email: "",
                password: "",
                is_demo: false,
            },
            loading: false,
            dialogVisible: false,
            rules: {
                email: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите адрес электронной почты",
                        trigger: "blur",
                    },
                    {
                        type: "email",
                        message: "Пожалуйста, введите корректный адрес электронной почты",
                        trigger: ["blur", "change"],
                    },
                ],
                login: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите адрес электронной почты",
                        trigger: "blur",
                    },
                ],
                password: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите пароль",
                        trigger: "blur",
                    },
                    {
                        required: true,
                        message: "Минимальная длинна пароля должна быть не менее 5 символов",
                        trigger: ["blur", "change"],
                        min: 5,
                    },
                ],
                is_demo: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите демо режим",
                        trigger: "change",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["create"]),
        submitForm: function () {
            this.$refs["createUser"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.create({
                        password: this.fields.password,
                        login: this.fields.login,
                        email: this.fields.email,
                        is_demo: this.fields.is_demo ? 1 : 0,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.login = "";
                        this.fields.email = "";
                        this.fields.password = "";
                        this.fields.is_demo = false;
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
};
</script>

<style scoped>
.is_demo {
    display: flex;
}
</style>
