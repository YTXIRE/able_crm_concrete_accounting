<template>
    <div class="wrapper">
        <Search />
        <CreateObject class="create" />
        <el-tabs type="border-card" @tab-click="set_tab">
            <el-tab-pane :name="String(0)" label="Активные">
                <el-table
                    v-loading="loading"
                    :data="objects.objects"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
                    <el-table-column label="Название" prop="name" sortable />
                    <el-table-column label="Действие" width="145">
                        <template #default="scope">
                            <EditObject :data="scope.row" class="block" />
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
                    :page-size="getSearchWordObject?.length > 0 ? 1000 : 10"
                    :total="Number(objects.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_object"
                    @prev-click="get_object"
                    @current-change="get_object"
                ></el-pagination>
            </el-tab-pane>
            <el-tab-pane :name="String(1)" label="Архивные" v-if="archive_objects.objects?.length">
                <el-table
                    v-loading="loading"
                    :data="archive_objects.objects"
                    :default-sort="{ prop: 'id', order: 'descending' }"
                    class="gray"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column label="ID" prop="id" sortable width="80" />
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
                    :total="Number(archive_objects.count)"
                    background
                    class="pagination"
                    hide-on-single-page
                    layout="prev, pager, next"
                    @next-click="get_object"
                    @prev-click="get_object"
                    @current-change="get_object"
                ></el-pagination>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import CreateObject from "./CreateObject";
import { mapActions, mapGetters } from "vuex";
import EditObject from "./EditObject";
import Search from "@/components/Objects/Search";

export default {
    components: {
        Search,
        CreateObject,
        EditObject
    },
    data() {
        return {
            loading: false,
            is_demo: false,
            objects: [],
            current_tab: 0,
            archive_objects: [],
            pagination: {
                0: 1,
                1: 1
            },
            offsets: {
                0: 0,
                1: 0
            }
        };
    },
    methods: {
        ...mapActions(["getAllObjects", "getAllVendors", "deleteObject", "restoreObject", "searchObject"]),
        get_object: function(e) {
            this.offsets[this.current_tab] = e * 10 - 10;
            this.pagination[this.current_tab] = e;
            this.get_objects();
        },
        async get_objects() {
            this.loading = true;
            this.objects = await this.getAllObjects({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[0],
                limit: 10,
                archive: 0
            });
            this.archive_objects = await this.getAllObjects({
                token: localStorage.getItem("crm_token"),
                offset: this.offsets[1],
                limit: 10,
                archive: 1
            });
            this.loading = false;
        },
        remove: async function(id) {
            if (this.archive_objects?.legal_entities?.length < 2) {
                this.offsets[0] = 0;
                this.pagination[0] = 0;
            }
            await this.deleteObject({
                id,
                token: localStorage.getItem("crm_token")
            });
        },
        restore: async function(id) {
            if (this.archive_objects?.legal_entities?.length < 2) {
                this.offsets[1] = 0;
                this.pagination[1] = 0;
            }
            await this.restoreObject({
                id,
                token: localStorage.getItem("crm_token")
            });
        },
        set_tab: function(e) {
            this.current_tab = e.props.name;
        },
        async search_objects(query) {
            this.loading = true;
            this.objects = await this.searchObject({
                token: localStorage.getItem("crm_token"),
                query: query
            });
            this.loading = false;
        }
    },
    async mounted() {
        await this.get_objects();
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    },
    computed: {
        ...mapGetters(["getIsNewObject", "getIsSearchObject", "getSearchWordObject"])
    },
    watch: {
        getIsNewObject: async function() {
            await this.get_objects();
        },
        getIsSearchObject: async function() {
            await this.search_objects(this.getSearchWordObject);
        }
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

.wrapper_vendor {
    position: relative;
}

.icon {
    font-size: 30px;
}

.text {
    position: absolute;
    margin-left: 10px;
    margin-top: 5px;
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
