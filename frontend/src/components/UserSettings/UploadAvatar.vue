<template>
    <p>Сменить аватар</p>
    <div v-loading="loading">
        <el-upload
            v-if="visibleBtn"
            :auto-upload="false"
            :on-change="upload"
            action="#"
            class="avatar-uploader"
            list-type="picture-card"
            :disabled="!is_demo"
        >
            <i class="el-icon-plus"></i>
        </el-upload>
        <img v-else :src="dialogImageUrl" alt="" class="avatar" />
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    data() {
        return {
            dialogImageUrl: "",
            visibleBtn: true,
            loading: false,
            is_demo: false,
        };
    },
    methods: {
        ...mapActions(["upload_avatar"]),
        async upload(file) {
            this.dialogImageUrl = URL.createObjectURL(file.raw);
            this.visibleBtn = false;
            this.loading = true;
            const user_data = JSON.parse(localStorage.getItem("user_data"));
            const files = document.querySelector(".el-upload__input").files[0];
            await this.upload_avatar({
                avatar: files,
                id: user_data.id,
                token: localStorage.getItem("crm_token"),
            });
            this.dialogImageUrl = "";
            this.visibleBtn = true;
            this.loading = false;
        },
    },
    mounted() {
        this.is_demo = +localStorage.getItem("is_demo") === 0;
    }
};
</script>

<style scoped>
img {
    width: 200px;
    height: 200px;
}
</style>
