<template>
    <div>
        <el-tabs type="border-card">
            <el-tab-pane label="Базовый">
                <Base />
            </el-tab-pane>
            <el-tab-pane label="Расширенный">
                <Advanced />
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import Base from "@/components/Reports/Base.vue";
import Advanced from "@/components/Reports/Advanced.vue";

export default {
    components: {
        Advanced,
        Base
    }
};
</script>

<style scoped></style>
