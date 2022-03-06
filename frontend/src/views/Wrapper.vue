<template>
    <div class="common-layout">
        <el-container>
            <el-header v-if="getIsAuth">
                <Header />
            </el-header>
            <el-container>
                <el-aside v-if="getIsAuth">
                    <Menu />
                </el-aside>
                <Main>
                    <slot></slot>
                </Main>
            </el-container>
        </el-container>
    </div>
</template>

<script>
import Header from "../components/Header";
import Menu from "../components/Menu";
import Main from "../components/Main";
import { mapGetters } from "vuex";

export default {
    components: {
        Header,
        Menu,
        Main,
    },
    computed: {
        ...mapGetters(["getIsAuth"]),
    },
};
</script>

<style>
.el-header {
    background-color: #f2f6fc;
    color: var(--el-text-color-primary);
    line-height: 60px;
    border-bottom: 1px solid #ddd;
}

.common-layout {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.el-aside {
    background-color: #ebeef5;
    color: var(--el-text-color-primary);
    text-align: center;
    width: 140px !important;
    border-right: 1px solid #ddd;
    display: block;
    flex-grow: 0;
}

.el-main {
    background-color: #e9eef3;
    color: var(--el-text-color-primary);
    text-align: center;
    display: block;
    flex-grow: 3;
}
</style>
