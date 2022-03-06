<template>
    <el-button class="edit_user" type="primary" @click="dialogVisible = true">
        <font-awesome-icon icon="edit" />
    </el-button>
    <el-dialog v-model="dialogVisible" title="Редактирование пользователя">
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
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">Отменить</el-button>
                <el-button :disabled="loading" type="primary" @click="submitForm"> Сохранить </el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script>
import { mapActions } from "vuex";

export default {
    props: {
        data: {
            require: true,
            type: Object,
        },
    },
    data() {
        return {
            fields: {
                login: "",
                email: "",
                password: "",
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
            },
        };
    },
    methods: {
        ...mapActions(["update_info"]),
        submitForm: function () {
            this.$refs["createUser"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.update_info({
                        id: this.data.id,
                        login: this.fields.login,
                        email: this.fields.email,
                        password: this.fields.password,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.login = "";
                        this.fields.email = "";
                        this.fields.password = "";
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
        this.fields.login = this.data.login;
        this.fields.email = this.data.email;
    },
};
</script>

<style scoped>
.edit_user {
    margin-right: 10px;
}
</style>
