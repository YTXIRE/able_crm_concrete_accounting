<template>
    <div class="wrapper">
        <Search />
        <CreateMaterial :material_types="material_types.types" class="create" />
        <el-card shadow="never">
            <el-table
                v-loading="loading"
                :data="materials.materials"
                :default-sort="{ prop: 'id', order: 'descending' }"
                empty-text="Данных нет"
                style="width: 100%"
            >
                <el-table-column label="ID" prop="id" sortable width="80" />
                <el-table-column label="Тип" prop="type" sortable />
                <el-table-column label="Название" prop="name" sortable />
                <el-table-column label="Действие" width="85">
                    <template #default="scope">
                        <EditMaterial :data="scope.row" :material_types="material_types.types" />
                    </template>
                </el-table-column>
            </el-table>
            <br />
            <el-pagination
                :page-size="getSearchWordMaterial?.length > 0 ? 1000 : 10"
                :total="Number(materials.count)"
                background
                class="pagination"
                hide-on-single-page
                layout="prev, pager, next"
                @next-click="get_material"
                @prev-click="get_material"
                @current-change="get_material"
            ></el-pagination>
        </el-card>
    </div>
</template>

<script>
import CreateMaterial from "./CreateMaterial";
import { mapActions, mapGetters } from "vuex";
import EditMaterial from "./EditMaterial";
import Search from "@/components/Materials/Search";

export default {
    components: {
        Search,
        CreateMaterial,
        EditMaterial
    },
    data() {
        return {
            materials: [],
            loading: false,
            material_types: [],
            offset: 0
        };
    },
    methods: {
        ...mapActions(["getAllMaterials", "getAllMaterialTypes", 'searchMaterial']),
        async get_materials() {
            this.loading = true;
            this.materials = await this.getAllMaterials({
                token: localStorage.getItem("crm_token"),
                offset: this.offset,
                limit: 10
            });
            this.loading = false;
        },
        get_material: function(e) {
            this.offset = e * 10 - 10;
            this.get_materials();
        },
        async search_materials(query) {
            this.loading = true;
            this.materials = await this.searchMaterial({
                token: localStorage.getItem("crm_token"),
                query: query,
            });
            this.loading = false;
        },
    },
    async mounted() {
        await this.get_materials();
        this.material_types = await this.getAllMaterialTypes({
            token: localStorage.getItem("crm_token")
        });
    },
    computed: {
        ...mapGetters(["getIsNewMaterial", 'getIsSearchMaterial', 'getSearchWordMaterial'])
    },
    watch: {
        getIsNewMaterial: async function() {
            await this.get_materials();
        },
        getIsSearchMaterial: async function() {
            await this.search_materials(this.getSearchWordMaterial);
        },
    }
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
