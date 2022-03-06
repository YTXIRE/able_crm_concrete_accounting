import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import ElementPlus from "element-plus";
import "element-plus/dist/index.css";
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import ru from "element-plus/es/locale/lang/ru";
import excel from "vue-excel-export";
import print from "vue3-print-nb";

library.add(fas, fab, far);

createApp(App)
    .use(router)
    .use(store)
    .use(ElementPlus, {
        locale: ru,
    })
    .use(excel)
    .use(print)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount("#app");
