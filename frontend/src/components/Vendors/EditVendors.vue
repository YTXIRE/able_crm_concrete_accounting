<template>
    <div>
        <el-button type="primary" @click="get_data">
            <font-awesome-icon icon="edit" />
        </el-button>
        <el-dialog v-model="dialogVisible" center title="Редактирование поставщика">
            <el-form ref="updateVendor" :model="fields" label-width="130px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="30" show-word-limit></el-input>
                </el-form-item>
                <el-form-item :disabled="loading" label="Иконка">
                    <Icons :icons="icons" />
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="btn_footer">
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
        data: {
            require: true,
            type: Object,
        },
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
        ...mapActions(["updateVendor", "setIcon"]),
        submitForm: function () {
            this.$refs["updateVendor"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.updateVendor({
                        id: this.data.id,
                        name: this.fields.name,
                        icon_id: this.getIcon.id !== 0 ? this.getIcon.id : this.fields.icon_id,
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
        get_data: function () {
            this.dialogVisible = true;
            this.options = this.icons;
        },
    },
    computed: {
        ...mapGetters(["getIcon"]),
    },
    async mounted() {
        this.fields.name = this.data.name;
        this.fields.icon_id = this.data.icon_id;
    },
    watch: {
        data(e) {
            this.fields.name = e.name;
            this.fields.icon_id = e.icon_id;
        },
    },
};
</script>

<style scoped></style>
