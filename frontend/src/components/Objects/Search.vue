<template>
    <div class="query_object">
        <el-input v-model="query" maxlength="100" show-word-limit class="query_object_field" @keydown.enter="search"></el-input>
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
        ...mapActions(['setSearchWordObject', 'setIsSearchObject']),
        async search() {
            this.setSearchWordObject(this.query)
            this.setIsSearchObject(Math.random())
        },
    }
};
</script>

<style scoped>
.query_object_field {
    width: 400px;
    margin-right: 5px;
}

.query_object {
    position: absolute;
    right: 0;
    top: -49px;
}
</style>