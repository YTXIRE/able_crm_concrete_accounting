<template>
    <div class="query_object">
        <el-input v-model="query" maxlength="100" show-word-limit class="query_object_field" @keydown.enter="search"
                  placeholder="Введите название объекта"></el-input>
        <el-button type="primary" @click="search" :disabled="active_btn">
            <font-awesome-icon icon="search" />
        </el-button>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "Search",
    data() {
        return {
            query: "",
            active_btn: true
        };
    },
    methods: {
        ...mapActions(["setSearchWordObject", "setIsSearchObject", "setIsNewObject"]),
        async search() {
            this.setSearchWordObject(this.query);
            this.setIsSearchObject(Math.random());
        }
    },
    watch: {
        query: function() {
            if (this.query.length > 0 && this.active_btn === true) {
                this.active_btn = false;
            }
            if (this.query.length === 0 && this.active_btn === false) {
                this.active_btn = true;
                this.setIsNewObject(Math.random());
                this.setSearchWordObject('');
            }
        }
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