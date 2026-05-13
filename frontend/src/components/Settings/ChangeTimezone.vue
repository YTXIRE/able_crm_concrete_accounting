<template>
    <el-popconfirm
        :title="`Ваш текущий часовой пояс ${this.currentTimezone}. Часовой пояс системы ${this.systemTimezone}. Сменить?`"
        cancel-button-text="Нет, оставить так"
        confirm-button-text="Да, сменить"
        icon="el-icon-info"
        icon-color="red"
        @confirm="confirmEvent"
    >
        <template #reference>
            <el-button :disabled="!is_demo || this.currentTimezone === this.systemTimezone" type="text">
                {{ time }}
            </el-button>
        </template>
    </el-popconfirm>
</template>

<script>
import { mapActions } from "vuex";

export default {
    props: {
        time: {
            require: true,
            type: String,
        },
    },
    data() {
        return {
            currentTimezone: null,
            systemTimezone: null,
            is_demo: false,
        };
    },
    methods: {
        ...mapActions(["setUserTimezone", "getTimezone"]),
        async confirmEvent() {
            const token = localStorage.getItem("crm_token");
            const timezone_id = await this.getTimezone({
                token,
                timezone_name: this.currentTimezone,
            });
            await this.setUserTimezone({
                timezone_id,
                token,
            });
            this.currentTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            this.systemTimezone = JSON.parse(localStorage.getItem("user_data"))?.timezone;
        },
    },
    mounted() {
        this.currentTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        this.systemTimezone = JSON.parse(localStorage.getItem("user_data"))?.timezone;
        this.is_demo = +localStorage.getItem("is_demo") === 0;

        setTimeout(() => {
            if (this.is_demo && this.currentTimezone !== this.systemTimezone) {
                document.querySelector(".el-button ").click();
            }
        }, 3000);
    },
};
</script>

<style scoped>
.el-button {
    color: #afafaf;
}
</style>
