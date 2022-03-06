<template>
    <div>
        <el-button circle type="success" @click="dialogVisible = true">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание нового поставщика">
            <el-form ref="createVendor" :model="fields" label-width="130px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="30" show-word-limit></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" :rules="rules.icon_id" label="Иконка" prop="icon_id">
                    <Icons :icons="icons" />
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
import { mapActions, mapGetters } from "vuex";
import Icons from "./Icons";

export default {
    props: {
        icons: {
            require: true,
            type: Object,
        },
    },
    components: {
        Icons,
    },
    data() {
        return {
            fields: {
                name: "",
            },
            options: [],
            loading: false,
            dialogVisible: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Пожалуйста, укажите название поставщика",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["saveVendor", "setIcon"]),
        submitForm: function () {
            this.$refs["createVendor"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.saveVendor({
                        name: this.fields.name,
                        icon_id: this.getIcon.id !== 0 ? this.getIcon.id : 1,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.name = "";
                        this.dialogVisible = false;
                        this.setIcon({
                            id: 0,
                            prefix: "",
                            name: "",
                        });
                    } else {
                        this.loading = false;
                    }
                } else {
                    this.loading = false;
                    this.setIcon({
                        id: 0,
                        prefix: "",
                        name: "",
                    });
                    return false;
                }
            });
        },
    },
    computed: {
        ...mapGetters(["getIcon"]),
    },
};
</script>

<style scoped></style>
