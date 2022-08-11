<template>
    <div class="query_material">
        <el-input v-model="query" maxlength="100" show-word-limit class="query_material_field" @keydown.enter="search"
                  placeholder="Введите название материала"></el-input>
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
        ...mapActions(["setSearchWordMaterial", "setIsSearchMaterial", "setIsNewMaterial"]),
        async search() {
            this.setSearchWordMaterial(this.query);
            this.setIsSearchMaterial(Math.random());
        }
    },
    watch: {
        query: function() {
            if (this.query.length > 0 && this.active_btn === true) {
                this.active_btn = false;
            }
            if (this.query.length === 0 && this.active_btn === false) {
                this.active_btn = true;
                this.setIsNewMaterial(Math.random());
                this.setSearchWordMaterial("");
            }
        }
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