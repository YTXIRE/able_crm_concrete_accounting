<template>
    <el-tabs type="border-card">
        <el-tab-pane label="Настройка часового пояса">
            <TimeZone />
        </el-tab-pane>
        <el-tab-pane v-if="is_admin" label="Пользователи">
            <Users />
        </el-tab-pane>
        <el-tab-pane v-if="is_admin" label="Итог на конец года">
            <Debt />
        </el-tab-pane>
    </el-tabs>
</template>

<script>
import TimeZone from "./TimeZone";
import Users from "./Users";
import Debt from "@/components/Settings/Debt.vue";

export default {
    components: {
        TimeZone,
        Users,
        Debt,
    },
    data() {
        return {
            is_admin: JSON.parse(localStorage.getItem("user_data"))?.rights?.is_admin,
        };
    },
    methods: {},
};
</script>

<style scoped>
.el-tabs {
    width: 1400px !important;

    margin: 0 auto;
}
</style>
