<template>
    <div class="wrapper">
        <CreateVendors :icons="icons" class="create" />
        <el-tabs type="border-card" @tab-click="set_tab">
            <el-tab-pane :name="String(0)" label="Активные">
                <el-table
                    v-loading="loading"
                    :data="vendors.vendors"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
                    <el-table-column label="Иконка" prop="icon" sortable width="100">
                        <template #default="scope">
                            <font-awesome-icon :icon="[scope.row.prefix, scope.row.icon]" class="icon" />
                        </template>
                    </el-table-column>
                    <el-table-column label="Название" prop="name" sortable />
                    <el-table-column label="Действие" width="145">
                        <template #default="scope">
                            <EditVendors :data="scope.row" :icons="icons" class="block" />
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
                    :current-page="pagination[current_tab]"
                    :page-size="10"
                    :total="Number(vendors.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_vendor"
                    @prev-click="get_vendor"
                    @current-change="get_vendor"
                ></el-pagination>
            </el-tab-pane>
            <el-tab-pane :name="String(1)" label="Архивные" v-if="archive_vendors.vendors?.length">
                <el-table
                    v-loading="loading"
                    :data="archive_vendors.vendors"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    class="gray"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
                    <el-table-column label="Иконка" prop="icon" sortable width="100">
                        <template #default="scope">
                            <font-awesome-icon :icon="[scope.row.prefix, scope.row.icon]" class="icon" />
                        </template>
                    </el-table-column>
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
                    :total="Number(archive_vendors.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_vendor"
                    @prev-click="get_vendor"
                    @current-change="get_vendor"
                ></el-pagination>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import CreateVendors from "./CreateVendors";
import { mapActions, mapGetters } from "vuex";
import EditVendors from "./EditVendors";

export default {
    components: {
        CreateVendors,
        EditVendors,
    },
    data() {
        return {
            icons: [],
            loading: false,
            is_demo: false,
            vendors: [],
            current_tab: 0,
            archive_vendors: [],
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
        ...mapActions(["getAllVendors", "getAllIcons", "deleteVendor", "restoreVendor"]),
        get_vendor: function (e) {
            this.offsets[this.current_tab] = e * 10 - 10;
            this.pagination[this.current_tab] = e;
            this.get_vendors();
        },
        async get_vendors() {
            this.loading = true;
            this.vendors = await this.getAllVendors({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[0],
                limit: 10,
                archive: 0,
            });
            this.archive_vendors = await this.getAllVendors({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[1],
                limit: 10,
                archive: 1,
            });
            this.loading = false;
        },
        remove: async function (id) {
            if (this.archive_vendors?.legal_entities?.length < 2) {
                this.offsets[0] = 0;
                this.pagination[0] = 0;
            }
            await this.deleteVendor({
                id,
                token: localStorage.getItem("crm_token"),
            });
        },
        restore: async function (id) {
            if (this.archive_vendors?.legal_entities?.length < 2) {
                this.offsets[1] = 0;
                this.pagination[1] = 0;
            }
            await this.restoreVendor({
                id,
                token: localStorage.getItem("crm_token"),
            });
        },
        set_tab: function (e) {
            this.current_tab = e.props.name;
        },
    },
    async mounted() {
        await this.get_vendors();
        this.icons = await this.getAllIcons({
            token: localStorage.getItem("crm_token"),
            offset: 0,
        });
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    computed: {
        ...mapGetters(["getIsNewVendor"]),
    },
    watch: {
        getIsNewVendor: async function () {
            await this.get_vendors();
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

.icon {
    font-size: 50px;
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
