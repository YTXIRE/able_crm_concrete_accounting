<template>
    <div>
        <el-menu :default-active="String(index)" class="el-menu" mode="vertical" @select="changeMenu">
            <el-menu-item :class="{ activeMenu: index === '1' }" class="menu-item" index="1">
                <font-awesome-icon class="menu-icon" icon="chart-line" />
                <div class="menu-text">Dashboard</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '3' }" class="menu-item" index="3">
                <font-awesome-icon class="menu-icon" icon="history" />
                <div class="menu-text">История операций</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '4' }" class="menu-item" index="4">
                <font-awesome-icon class="menu-icon" icon="money-bill-alt" />
                <div class="menu-text">Оплата</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '5' }" class="menu-item" index="5">
                <font-awesome-icon class="menu-icon" icon="truck-pickup" />
                <div class="menu-text">Поставщики</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '6' }" class="menu-item" index="6">
                <font-awesome-icon class="menu-icon" icon="hard-hat" />
                <div class="menu-text">Объекты</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '7' }" class="menu-item" index="7">
                <font-awesome-icon class="menu-icon" icon="drafting-compass" />
                <div class="menu-text">Материалы</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '8' }" class="menu-item" index="8">
                <font-awesome-icon class="menu-icon" icon="paint-roller" />
                <div class="menu-text">Типы материалов</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '9' }" class="menu-item" index="9">
                <font-awesome-icon class="menu-icon" icon="building" />
                <div class="menu-text">Юридические лица</div>
            </el-menu-item>
            <el-menu-item :class="{ activeMenu: index === '10' }" class="menu-item" index="10">
                <font-awesome-icon class="menu-icon" icon="file-code" />
                <div class="menu-text">Отчеты</div>
            </el-menu-item>
        </el-menu>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            index: 1,
        };
    },
    methods: {
        ...mapActions(["setIndexMenu", "setActiveSettings", "setActiveUserSettings"]),
        changeMenu: function (key) {
            this.setIndexMenu(key);
            this.setActiveSettings(false);
            this.setActiveUserSettings(false);
            localStorage.setItem("currentMenu", String(key));
            switch (key) {
                case "1":
                    this.$router.push("/dashboard");
                    break;
                case "3":
                    this.$router.push("/history");
                    break;
                case "4":
                    this.$router.push("/payments");
                    break;
                case "5":
                    this.$router.push("/vendors");
                    break;
                case "6":
                    this.$router.push("/objects");
                    break;
                case "7":
                    this.$router.push("/materials");
                    break;
                case "8":
                    this.$router.push("/material_types");
                    break;
                case "9":
                    this.$router.push("/legal_entities");
                    break;
                case "10":
                    this.$router.push("/reports");
                    break;
            }
        },
    },
    computed: {
        ...mapGetters(["getIndexMenu"]),
    },
    watch: {
        getIndexMenu: function () {
            this.index = this.getIndexMenu;
        },
    },
    mounted() {
        if (window.location.pathname === "/") {
            localStorage.setItem("currentMenu", "1");
            this.setActiveSettings(false);
            this.setActiveUserSettings(false);
        }
        this.index = localStorage.getItem("currentMenu") ?? this.getIndexMenu;
    },
};
</script>

<style scoped>
.el-menu {
    background: transparent;
}

.el-menu-item {
    height: auto;
    color: #afafaf;
}

.menu-icon {
    font-size: 35px !important;
}

.menu-text {
    font-size: 12px;
    margin-top: -20px;
}

.menu-item:hover {
    color: cornflowerblue !important;
}

.activeMenu svg,
.activeMenu div {
    color: #409eff;
}
</style>
