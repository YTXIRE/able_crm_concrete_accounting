<template>
    <el-tabs type="border-card">
        <el-tab-pane label="Личные данные">
            <UserData />
        </el-tab-pane>
        <el-tab-pane label="Пароль">
            <ChangePassword />
        </el-tab-pane>
        <el-tab-pane label="Аватарка">
            <UploadAvatar />
        </el-tab-pane>
    </el-tabs>
</template>

<script>
import UserData from "./UserData";
import ChangePassword from "./ChangePassword";
import UploadAvatar from "./UploadAvatar";

export default {
    components: {
        UserData,
        ChangePassword,
        UploadAvatar,
    },
    data() {
        return {};
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
