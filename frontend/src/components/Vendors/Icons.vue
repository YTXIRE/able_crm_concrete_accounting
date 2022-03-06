<template>
    <div>
        <el-button class="add_icon" type="info" @click="get_icons">
            <font-awesome-icon :icon="[getIcon.prefix, getIcon.name]" class="icon_btn" />
            Выбрать иконку
        </el-button>
        <el-dialog v-model="dialogVisible" center fullscreen title="Иконки">
            <el-row :gutter="12">
                <el-col v-for="icon in options.icons" :key="icon.id" :span="4">
                    <el-card class="icon" shadow="hover" @click="select_icon(icon.id, icon.prefix, icon.name)">
                        <font-awesome-icon :icon="[icon.prefix, icon.name]" />
                    </el-card>
                </el-col>
            </el-row>
            <el-pagination
                :current-page="current_page"
                :page-size="48"
                :total="Number(options.count)"
                background
                class="pagination"
                layout="prev, pager, next"
                @next-click="pagination"
                @prev-click="pagination"
                @current-change="pagination"
            ></el-pagination>
            <template #footer>
                <span class="dialog-footer">
                    <el-button :disabled="loading" @click.prevent="dialogVisible = false"> Отменить </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    props: {
        icons: {
            require: true,
            type: Object,
        },
    },
    data() {
        return {
            dialogVisible: false,
            options: [],
            current_page: 1,
            loading: false,
        };
    },
    methods: {
        ...mapActions(["getAllIcons", "setIcon"]),
        get_icons: function () {
            this.options = this.icons;
            this.dialogVisible = true;
        },
        pagination: function (e) {
            this.current_page = e;
            this.get_icons_with_api(e * 48 - 48);
        },
        async get_icons_with_api(offset) {
            this.options = await this.getAllIcons({
                token: localStorage.getItem("crm_token"),
                offset: offset,
            });
        },
        select_icon: function (id, prefix, name) {
            this.loading = true;
            this.dialogVisible = false;
            this.setIcon({
                id,
                prefix,
                name,
            });
            this.current_page = 1;
            this.loading = false;
        },
    },
    computed: {
        ...mapGetters(["getIcon"]),
    },
    async mounted() {},
};
</script>

<style scoped>
.add_icon {
    width: 100%;
}

.icon {
    font-size: 30px;
    text-align: center;
}

.el-col {
    cursor: pointer;
    padding-bottom: 12px;
}

.icon_btn {
    font-size: 20px;
    padding-right: 10px;
}

.pagination {
    text-align: center;
}
</style>
