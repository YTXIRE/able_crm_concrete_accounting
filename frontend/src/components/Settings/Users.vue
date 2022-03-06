<template>
    <div class="wrapper">
        <CreateUser class="create" />
        <el-table v-loading="loading" :data="users.users" empty-text="Данных нет" style="width: 100%">
            <el-table-column label="ID" prop="id" width="80" />
            <el-table-column label="Логин" prop="login" width="380" />
            <el-table-column label="Email" prop="email" />
            <el-table-column label="Действия" width="150">
                <template #default="scope">
                    <EditUser :data="scope.row" />
                    <el-popconfirm
                        confirm-button-text="Естественно"
                        cancel-button-text="Стой! Оставь"
                        icon-color="red"
                        title="Вы точно хотите удалить?"
                        @confirm="remove_user(scope.row.id)"
                    >
                        <template #reference>
                            <el-button type="danger">
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
            :total="Number(users.count)"
            background
            class="pagination"
            hide-on-single-page
            layout="prev, pager, next"
            @next-click="get_user"
            @prev-click="get_user"
            @current-change="get_user"
        ></el-pagination>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import CreateUser from "./Users/CreateUser";
import EditUser from "./Users/EditUser";

export default {
    components: {
        CreateUser,
        EditUser,
    },
    data() {
        return {
            loading: false,
            users: [],
            offset: 0,
        };
    },
    methods: {
        ...mapActions(["get_all", "delete"]),
        async get_users() {
            this.loading = true;
            this.users = await this.get_all({
                token: localStorage.getItem("crm_token"),
                offset: this.offset,
                limit: 10,
            });
            this.loading = false;
        },
        get_user: function (e) {
            this.offset = e * 10 - 10;
            this.get_users();
        },
        remove_user: function (id) {
            this.delete({
                id,
                token: localStorage.getItem("crm_token"),
            });
        },
    },
    computed: {
        ...mapGetters(["getIsCreateUser"]),
    },
    watch: {
        getIsCreateUser: async function () {
            await this.get_users();
        },
    },
    async mounted() {
        await this.get_users();
    },
};
</script>

<style scoped>
.wrapper {
    position: relative;
    margin: auto;
}

.create {
    z-index: 999;
    top: 0px;
    position: absolute;
    right: 0px;
}
</style>
