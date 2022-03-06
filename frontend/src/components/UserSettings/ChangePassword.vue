<template>
    <el-form ref="dynamicValidateForm" :model="fields" label-width="80px">
        <el-form-item :disabled="loading" :rules="rules.password" label="Пароль" prop="password">
            <el-input v-model="fields.password" maxlength="100" show-word-limit type="password"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button :disabled="loading" type="primary" @click="submitForm('dynamicValidateForm')">
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
            fields: {
                password: "",
            },
            rules: {
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
        ...mapActions(["change_password"]),
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const user_data = JSON.parse(localStorage.getItem("user_data"));
                    const result = await this.change_password({
                        password: this.fields.password,
                        id: user_data.id,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.password = "";
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
        const data = JSON.parse(localStorage.getItem("user_data"));
        this.fields.email = data.email;
        this.fields.login = data.login;
    },
};
</script>

<style scoped>
.el-button {
    margin-left: -80px;
}
</style>
