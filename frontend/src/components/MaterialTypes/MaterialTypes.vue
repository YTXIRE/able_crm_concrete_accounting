<template>
    <div class="wrapper">
        <AddMaterialType :units_measurement_volume="units_measurement_volume" class="create" />
        <el-card shadow="never">
            <el-table
                v-loading="loading"
                :data="material_types.types"
                :default-sort="{ prop: 'id', order: 'descending' }"
                empty-text="Данных нет"
                style="width: 100%"
            >
                <el-table-column label="ID" prop="id" sortable width="80" />
                <el-table-column label="Величина объема" prop="units_measurement_volume.name" sortable width="260" />
                <el-table-column label="Название" prop="name" sortable />
                <el-table-column label="Действие" width="85">
                    <template #default="scope">
                        <EditMaterialType :data="scope.row" :units_measurement_volume="units_measurement_volume" />
                    </template>
                </el-table-column>
            </el-table>
            <br />
            <el-pagination
                :page-size="10"
                :total="Number(material_types.count)"
                background
                class="pagination"
                hide-on-single-page
                layout="prev, pager, next"
                @next-click="get_types"
                @prev-click="get_types"
                @current-change="get_types"
            ></el-pagination>
        </el-card>
    </div>
</template>

<script>
import AddMaterialType from "./CreateMaterialType";
import EditMaterialType from "./EditMaterialType";

import { mapActions, mapGetters } from "vuex";

export default {
    components: {
        AddMaterialType,
        EditMaterialType,
    },
    data() {
        return {
            material_types: [],
            units_measurement_volume: [],
            loading: false,
            offset: 0,
        };
    },
    methods: {
        ...mapActions(["getAllMaterialTypes", "getAllUnitsMeasurementVolume"]),
        async get_material_types() {
            this.loading = true;
            this.material_types = await this.getAllMaterialTypes({
                token: localStorage.getItem("crm_token"),
                offset: this.offset,
                limit: 10,
            });
            this.loading = false;
        },
        get_types: function (e) {
            this.offset = e * 10 - 10;
            this.get_material_types(this.offset);
        },
    },
    async mounted() {
        await this.get_material_types();
        this.units_measurement_volume = await this.getAllUnitsMeasurementVolume(localStorage.getItem("crm_token"));
    },
    computed: {
        ...mapGetters(["getIsNewMaterialType"]),
    },
    watch: {
        getIsNewMaterialType: async function () {
            await this.get_material_types();
        },
    },
};
</script>

<style scoped>
.wrapper {
    position: relative;
    margin: auto;
    width: 1400px !important;
}

.create {
    position: absolute;
    right: -50px;
}
</style>
