<template>
    <div class="query_material">
        <el-input v-model="query" maxlength="100" show-word-limit class="query_material_field" @keydown.enter="search"></el-input>
        <el-button type="primary" @click="search">
            <font-awesome-icon icon="search" />
        </el-button>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "Search",
    data() {
        return {
            query: "",
            loading: false
        };
    },
    methods: {
        ...mapActions(['setSearchWordMaterial', 'setIsSearchMaterial']),
        async search() {
            this.setSearchWordMaterial(this.query)
            this.setIsSearchMaterial(Math.random())
        },
    }
};
</script>

<style scoped>
.query_material_field {
    width: 400px;
    margin-right: 5px;
}

.query_material {
    position: absolute;
    right: 0;
    top: -49px;
}
</style>