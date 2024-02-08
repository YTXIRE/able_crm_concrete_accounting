<template>
    <div class="wrapper">
        <CreateLegalEntities :legal_entity_types="legal_entity_types" class="create" />
        <el-tabs type="border-card" @tab-click="set_tab">
            <el-tab-pane :name="String(0)" label="Активные">
                <el-table
                    v-loading="loading"
                    :data="legal_entities.legal_entities"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
                    <el-table-column label="Тип" prop="legal_entities_type" sortable width="100" />
                    <el-table-column label="Название" prop="name" sortable />
                    <el-table-column label="Действие" width="145">
                        <template #default="scope">
                            <EditLegalEntities
                                :data="scope.row"
                                :legal_entity_types="legal_entity_types"
                                class="block"
                            />
                            <el-popconfirm
                                confirm-button-text="Естественно"
                                cancel-button-text="Стой! Оставь"
                                icon-color="red"
                                title="Вы точно хотите удалить?"
                                @confirm="remove(scope.row.id)"
                            >
                                <template #reference>
                                    <el-button class="remove" type="danger" :disabled="!is_demo">
                                        <font-awesome-icon icon="trash" />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>
                <br />
                <el-pagination
                    :page-size="10"
                    :total="Number(legal_entities.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_entity"
                    @prev-click="get_entity"
                    @current-change="get_entity"
                ></el-pagination>
            </el-tab-pane>
            <el-tab-pane :name="String(1)" label="Архивные" v-if="archive_legal_entity_types.legal_entities?.length">
                <el-table
                    v-loading="loading"
                    :data="archive_legal_entity_types.legal_entities"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    class="gray"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
                    <el-table-column label="Тип" prop="legal_entities_type" sortable width="100" />
                    <el-table-column label="Название" prop="name" sortable />
                    <el-table-column label="Действие" width="85">
                        <template #default="scope">
                            <el-button class="remove" plain type="info" @click="restore(scope.row.id)" :disabled="!is_demo">
                                <font-awesome-icon icon="trash-restore" />
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <br />
                <el-pagination
                    :current-page="pagination[current_tab]"
                    :page-size="10"
                    :total="Number(archive_legal_entity_types.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_entity"
                    @prev-click="get_entity"
                    @current-change="get_entity"
                ></el-pagination>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import CreateLegalEntities from "./CreateLegalEntities";
import { mapActions, mapGetters } from "vuex";
import EditLegalEntities from "./EditLegalEntities";

export default {
    components: {
        CreateLegalEntities,
        EditLegalEntities,
    },
    data() {
        return {
            legal_entities: [],
            loading: false,
            is_demo: false,
            legal_entity_types: [],
            current_tab: 0,
            archive_legal_entity_types: [],
            pagination: {
                0: 1,
                1: 1,
            },
            offsets: {
                0: 0,
                1: 0,
            },
        };
    },
    methods: {
        ...mapActions(["getAllLegalEntities", "getAllLegalEntitiesTypes", "deleteLegalEntity", "restoreLegalEntity"]),
        get_entity: function (e) {
            this.offsets[this.current_tab] = e * 10 - 10;
            this.pagination[this.current_tab] = e;
            this.get_legal_entities(this.offset);
        },
        async get_legal_entities() {
            this.loading = true;
            this.legal_entities = await this.getAllLegalEntities({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[0],
                limit: 10,
                archive: 0,
            });
            this.archive_legal_entity_types = await this.getAllLegalEntities({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[1],
                limit: 10,
                archive: 1,
            });
            this.loading = false;
        },
        remove: async function (id) {
            if (this.archive_legal_entity_types?.legal_entities?.length < 2) {
                this.offsets[0] = 0;
                this.pagination[0] = 0;
            }
            await this.deleteLegalEntity({
                id,
                token: localStorage.getItem("crm_token"),
            });
        },
        restore: async function (id) {
            if (this.archive_legal_entity_types?.legal_entities?.length < 2) {
                this.offsets[1] = 0;
                this.pagination[1] = 0;
            }
            await this.restoreLegalEntity({
                id,
                token: localStorage.getItem("crm_token"),
            });
        },
        set_tab: function (e) {
            this.current_tab = e.props.name;
        },
    },
    async mounted() {
        await this.get_legal_entities();
        this.legal_entity_types = await this.getAllLegalEntitiesTypes(localStorage.getItem("crm_token"));
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    computed: {
        ...mapGetters(["getIsLegalEntities"]),
    },
    watch: {
        getIsLegalEntities: async function () {
            await this.get_legal_entities();
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

.block {
    display: inline;
}

.remove {
    margin-left: 10px;
}

.gray {
    color: #afafaf;
}
</style>
