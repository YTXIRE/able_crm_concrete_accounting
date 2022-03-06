<template>
    <div>
        <div>
            <el-checkbox v-model="isDebt" @change="setDebt" label="Включить долг на конец года"></el-checkbox>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    data() {
        return {
            isDebt: false,
        };
    },
    methods: {
        ...mapActions(["getDebt", "saveDebt"]),
        setDebt: async function () {
            await this.saveDebt({
                token: localStorage.getItem("crm_token"),
                active: Number(this.isDebt),
            });
        },
    },
    async mounted() {
        this.isDebt = await this.getDebt({
            token: localStorage.getItem("crm_token"),
        });
    },
};
</script>

<style scoped>
div {
    text-align: left;
}

.el-checkbox {
    margin-left: 10px;
}
</style>
