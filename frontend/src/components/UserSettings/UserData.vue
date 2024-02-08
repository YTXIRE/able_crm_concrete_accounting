<template>
    <el-form ref="dynamicValidateForm" :model="fields" label-width="80px">
        <el-form-item :disabled="loading" :rules="rules.login" label="Логин" prop="login">
            <el-input v-model="fields.login" maxlength="100" show-word-limit :disabled="!is_demo"></el-input>
        </el-form-item>
        <el-form-item :disabled="loading" :rules="rules.email" label="Email" prop="email">
            <el-input v-model="fields.email" maxlength="100" show-word-limit :disabled="!is_demo"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button :disabled="loading || !is_demo" type="primary" @click="submitForm('dynamicValidateForm')">
                Сохранить
            </el-button>
        </el-form-item>
    </el-form>
</template>

<script>
import { mapActions } from "vuex";

export default {
    data() {
        return {
            loading: false,
            is_demo: false,
            fields: {
                login: "",
                email: "",
            },
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
            },
        };
    },
    methods: {
        ...mapActions(["update_info"]),
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const user_data = JSON.parse(localStorage.getItem("user_data"));
                    await this.update_info({
                        login: this.fields.login,
                        email: this.fields.email,
                        id: user_data.id,
                        token: localStorage.getItem("crm_token"),
                    });
                    this.loading = false;
                } else {
                    this.loading = false;
                    return false;
                }
            });
        },
    },
    async mounted() {
        const data = JSON.parse(localStorage.getItem("user_data"));
        this.fields.email = data.email;
        this.fields.login = data.login;
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
};
</script>

<style scoped>
.el-button {
    margin-left: -80px;
}
</style>
