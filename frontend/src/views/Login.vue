<template>
    <div>
        <el-form ref="ruleForm" :model="ruleForm" :rules="rules" class="able_auth" label-width="120px" status-icon>
            <h2 class="text">Авторизация</h2>
            <el-form-item prop="login">
                <el-input
                    v-model="ruleForm.login"
                    :disabled="loading"
                    autocomplete="off"
                    placeholder="Логин"
                    type="text"
                    @keyup.enter="submitForm('ruleForm')"
                ></el-input>
            </el-form-item>
            <el-form-item prop="pass">
                <el-input
                    v-model="ruleForm.pass"
                    :disabled="loading"
                    autocomplete="off"
                    placeholder="Пароль"
                    type="password"
                    @keyup.enter="submitForm('ruleForm')"
                ></el-input>
            </el-form-item>
            <el-form-item>
                <el-button
                    v-loading="loading"
                    :disabled="loading"
                    class="btn-login"
                    type="primary"
                    @click="submitForm('ruleForm')"
                    @keyup.enter="submitForm('ruleForm')"
                    >Войти
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    props: {
        title: {
            type: String,
            require: true,
        },
    },
    data() {
        const checkLogin = (rule, value, callback) => {
            if (!value) {
                return callback(new Error("Пожалуйста, заполните поле логин"));
            } else {
                if (this.ruleForm.login !== "") {
                    this.$refs.ruleForm.validateField("login");
                }
                callback();
            }
        };
        const validatePass = (rule, value, callback) => {
            if (value === "") {
                callback(new Error("Пожалуйста, заполните поле пароль"));
            } else {
                if (this.ruleForm.pass !== "") {
                    this.$refs.ruleForm.validateField("pass");
                }
                callback();
            }
        };
        return {
            loading: false,
            ruleForm: {
                pass: "",
                login: "",
            },
            rules: {
                pass: [{ validator: validatePass, trigger: "blur" }],
                login: [{ validator: checkLogin, trigger: "blur" }],
            },
        };
    },
    methods: {
        ...mapActions(["setIsAuth", "login"]),
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    if (
                        await this.login({
                            login: this.ruleForm.login,
                            password: this.ruleForm.pass,
                        })
                    ) {
                        await this.$router.push("/dashboard");
                    }
                    this.loading = false;
                } else {
                    this.loading = false;
                    return false;
                }
            });
        },
    },
    beforeMount() {
        document.title = this.title;
    },
};
</script>

<style scoped>
.able_auth {
    margin-left: -50px;
    width: 500px;
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}

.btn-login {
    width: 380px;
}

.text {
    margin-left: 120px;
}
</style>
