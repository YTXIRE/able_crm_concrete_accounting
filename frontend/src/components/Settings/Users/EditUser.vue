<template>
    <el-button class="edit_user" type="primary" @click="dialogVisible = true">
        <font-awesome-icon icon="edit" />
    </el-button>
    <el-dialog v-model="dialogVisible" title="Редактирование пользователя">
        <el-form ref="editUser" :model="fields" label-width="80px">
            <el-form-item :disabled="loading" :rules="rules.login" label="Логин" prop="login">
                <el-input v-model="fields.login" maxlength="100" show-word-limit></el-input>
            </el-form-item>
            <el-form-item :disabled="loading" :rules="rules.email" label="Email" prop="email">
                <el-input v-model="fields.email" maxlength="100" show-word-limit></el-input>
            </el-form-item>
            <el-form-item :disabled="loading" :rules="rules.is_demo" label="Демо" prop="is_demo">
                <el-checkbox v-model="fields.is_demo" :label="fields.is_demo ? 'Да' : 'Нет'"  class="is_demo"></el-checkbox>
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
            type: Object
        }
    },
    data() {
        return {
            fields: {
                login: "",
                email: "",
                is_demo: false,
            },
            loading: false,
            dialogVisible: false,
            rules: {
                email: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите адрес электронной почты",
                        trigger: "blur"
                    },
                    {
                        type: "email",
                        message: "Пожалуйста, введите корректный адрес электронной почты",
                        trigger: ["blur", "change"]
                    }
                ],
                login: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите адрес электронной почты",
                        trigger: "blur"
                    }
                ],
                is_demo: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите режим",
                        trigger: "change"
                    }
                ]
            }
        };
    },
    methods: {
        ...mapActions(["update_info"]),
        submitForm: function() {
            this.$refs["editUser"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.update_info({
                        id: this.data.id,
                        login: this.fields.login,
                        email: this.fields.email,
                        is_demo: this.fields.is_demo ? 1 : 0,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.login = "";
                        this.fields.email = "";
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
        }
    },
    mounted() {
        this.fields.login = this.data.login;
        this.fields.email = this.data.email;
        this.fields.is_demo = !!this.data.is_demo;
    },
    watch: {
        data(e) {
            this.fields.login = e.login;
            this.fields.email = e.email;
            this.fields.is_demo = !!e.is_demo;
        },
    },
}
</script>

<style scoped>
.edit_user {
    margin-right: 10px;
}

.is_demo {
    display: flex;
}
</style>
