<template>
    <el-select
        v-model="value"
        :loading="loading"
        filterable
        no-data-text="Часовых поясов не найдено"
        placeholder="Выберите часовой пояс"
        @change="set_timezone"
    >
        <el-option
            v-for="item in timezones"
            :key="item.timezone_name"
            :label="item.timezone_name"
            :value="item.id"
            filterable
        >
            <span class="timezone">{{ item.timezone_name }}</span>
            <span class="time">
                {{ item.real_time }}
            </span>
        </el-option>
    </el-select>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            timezones: null,
            value: JSON.parse(localStorage.getItem("user_data"))?.timezone,
            loading: false,
        };
    },
    methods: {
        ...mapActions(["getTimezones", "setUserTimezone"]),
        set_timezone(timezone_id) {
            this.setUserTimezone({
                timezone_id,
                token: localStorage.getItem("crm_token"),
            });
        },
    },
    computed: {
        ...mapGetters(["getTimezone"]),
    },
    async mounted() {
        this.loading = true;
        this.timezones = await this.getTimezones(localStorage.getItem("crm_token"));
        this.timezones.forEach((timezone) => {
            let date = new Date();
            let strTime = date.toLocaleString("ru-RU", {
                timeZone: timezone.timezone_name,
            });
            timezone.real_time = strTime;
        });
        this.loading = false;
    },
};
</script>

<style scoped>
.el-select {
    width: 100%;
}

.time {
    float: right;
    color: var(--el-text-color-secondary);
    font-size: 13px;
}

.timezone {
    float: left;
}
</style>
