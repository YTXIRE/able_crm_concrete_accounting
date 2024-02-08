<template>
    <div>
        <el-button type="primary" @click="dialogVisible = true" :disabled="!is_demo">
            <font-awesome-icon icon="edit" />
        </el-button>
        <el-dialog v-model="dialogVisible" title="Обновление типа материала">
            <el-form ref="updateMaterialType" :model="fields" label-width="150px" @submit.prevent.stop>
                <el-form-item :disabled="loading" :rules="rules.name" label="Название" prop="name">
                    <el-input v-model="fields.name" maxlength="100" show-word-limit></el-input>
                </el-form-item>
                <el-form-item
                    :disabled="loading"
                    :rules="rules.units_measurement_volume_id"
                    label="Величина объема"
                    prop="units_measurement_volume_id"
                >
                    <el-select
                        v-model="fields.units_measurement_volume_id"
                        no-data-text="Величин объема не найдено"
                        placeholder="Выберите величину объема"
                    >
                        <el-option
                            v-for="item in units_measurement_volume"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
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
    props: {
        data: {
            require: true,
            type: Object,
        },
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
        ...mapActions(["updateMaterialType"]),
        submitForm: function () {
            this.$refs["updateMaterialType"].validate(async (valid) => {
                if (valid) {
                    this.loading = true;
                    const result = await this.updateMaterialType({
                        id: this.data.id,
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
        this.fields.name = this.data.name;
        this.fields.units_measurement_volume_id = this.data.units_measurement_volume.id;
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    watch: {
        data(e) {
            this.fields.name = e.name;
            this.fields.units_measurement_volume_id = e.units_measurement_volume.id;
        },
    },
};
</script>
<style scoped>
.el-select {
    width: 100%;
}
</style>
