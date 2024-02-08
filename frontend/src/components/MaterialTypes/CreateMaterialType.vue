<template>
    <div>
        <el-button type="success" circle @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="plus" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Создание типа материала">
            <el-form :model="fields" label-width="150px" ref="createMaterialType" @submit.prevent.stop>
                <el-form-item prop="name" label="Название" :rules="rules.name" :disabled="loading">
                    <el-input v-model="fields.name" maxlength="100" show-word-limit></el-input>
                </el-form-item>
                <el-form-item
                    prop="units_measurement_volume_id"
                    label="Величина объема"
                    :rules="rules.units_measurement_volume_id"
                    :disabled="loading"
                >
                    <el-select
                        v-model="fields.units_measurement_volume_id"
                        placeholder="Выберите величину объема"
                        no-data-text="Величин объема не найдено"
                    >
                        <el-option
                            v-for="item in units_measurement_volume"
                            :key="item.id"
                            :value="item.id"
                            :label="item.name"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click.prevent="dialogVisible = false" :disabled="loading">Отменить</el-button>
                    <el-button type="primary" @click.prevent="submitForm" :disabled="loading"> Сохранить </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    props: {
        units_measurement_volume: {
            require: true,
            type: Array,
        },
    },
    data() {
        return {
            fields: {
                name: "",
                units_measurement_volume_id: "",
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
                units_measurement_volume_id: [
                    {
                        required: true,
                        message: "Пожалуйста, выберите величину объема",
                        trigger: "blur",
                    },
                ],
            },
        };
    },
    methods: {
        ...mapActions(["saveMaterialType"]),
        submitForm: function () {
            this.$refs["createMaterialType"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.saveMaterialType({
                        name: this.fields.name,
                        units_measurement_volume_id: this.fields.units_measurement_volume_id,
                        token: localStorage.getItem("crm_token"),
                    });
                    if (result) {
                        this.loading = false;
                        this.fields.name = "";
                        this.fields.units_measurement_volume_id = "";
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
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    }
};
</script>
<style scoped>
.el-select {
    width: 100%;
}
</style>
