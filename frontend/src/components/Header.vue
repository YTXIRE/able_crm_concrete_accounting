<template>
    <div class="parent">
        <div class="logo">Able CRM</div>
        <div class="time">
            <ChangeTimezone :time="time" />
        </div>
        <div class="exit">
            <el-button :class="{ activeMenu: getActiveUserSettings }" type="text" @click="user_settings">
                <div class="login">
                    <div>
                        <el-avatar :src="avatar" icon="el-icon-user-solid" size="small"></el-avatar>
                    </div>
                    <div class="login_text">{{ getLogin }}</div>
                </div>
            </el-button>
            <el-button :class="{ activeMenu: getActiveSettings }" type="text" @click="settings">
                <font-awesome-icon class="icon" icon="cogs" />
            </el-button>
            <el-button type="text" @click="logout_system">
                <font-awesome-icon class="icon" icon="sign-out-alt" />
            </el-button>
        </div>
    </div>
</template>

<script>
import { logout } from "../api/auth";
import { mapActions, mapGetters } from "vuex";
import ChangeTimezone from "./Settings/ChangeTimezone";

export default {
    components: {
        ChangeTimezone,
    },
    data() {
        return {
            time: null,
            interval: null,
            user_data: JSON.parse(localStorage.getItem("user_data")) ?? null,
            avatar: null,
        };
    },
    created() {
        this.interval = setInterval(this.getNow, 1000);
    },
    methods: {
        ...mapActions(["setIsAuth", "setActiveSettings", "setIndexMenu", "setActiveUserSettings"]),
        getNow: function () {
            const today = new Date();
            this.time = today.toLocaleString("ru-RU", {
                timeZone: JSON.parse(localStorage.getItem("user_data"))?.timezone,
            });
        },
        logout_system: function () {
            if (logout(localStorage.getItem("crm_token"))) {
                this.setIsAuth(false);
                this.$router.push("/");
            }
        },
        settings: function () {
            this.setIndexMenu("0");
            this.setActiveSettings(true);
            this.setActiveUserSettings(false);
            localStorage.setItem("currentMenu", "0");
            this.$router.push("/settings");
        },
        user_settings: function () {
            this.setIndexMenu("0");
            this.setActiveSettings(false);
            this.setActiveUserSettings(true);
            localStorage.setItem("currentMenu", "999");
            this.$router.push("/user_settings");
        },
    },
    mounted() {
        this.avatar = this.getAvatar;
    },
    computed: {
        ...mapGetters(["getActiveSettings", "getActiveUserSettings", "getLogin", "getAvatar"]),
    },
    unmounted() {
        clearInterval(this.interval);
    },
    watch: {
        getAvatar: function () {
            this.avatar = this.getAvatar;
        },
    },
};
</script>

<style scoped>
.parent {
    display: flex;
    justify-content: space-between;
}

.logo {
    text-align: left;
    font-size: 30px;
    color: #409eff;
    font-weight: 700;
    text-shadow: 2px 2px 2px #bbb;
    display: block;
}

.time {
    display: block;
    margin-left: 160px;
    position: absolute;
    color: #afafaf;
}

.exit {
    display: flex;
    margin-top: 3px;
}

.el-button span svg {
    font-size: 25px;
    color: #bbb;
}

.el-button span svg:hover,
.login:hover {
    color: #409eff;
}

.login {
    margin-right: 30px;
    margin-top: -3px;
    color: #afafaf;
    font-size: 16px;
    display: flex;
}

.activeMenu span svg,
.activeMenu span div {
    color: #409eff;
}

.login_text {
    margin-top: 6px;
    margin-left: 15px;
}

.icon {
    margin-top: -5px;
    margin-right: 10px;
}
</style>
