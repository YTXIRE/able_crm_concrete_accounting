import { createRouter, createWebHashHistory } from "vue-router";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Vendors from "../views/Vendors";
import Payments from "../views/Payments";
import Settings from "../views/Settings";
import UserSettings from "../views/UserSettings";
import Materials from "../views/Materials";
import Objects from "../views/Objects";
import MaterialTypes from "../views/MaterialTypes";
import LegalEntities from "../views/LegalEntities";
import History from "../views/History.vue";
import Reports from "@/views/Reports.vue";

const title = (title) => `${title} | Able CRM`;

const routes = [
    {
        path: "/",
        name: "login",
        component: Login,
        props: {
            title: title("Авторизация"),
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        props: {
            title: title("Dashboard"),
        },
    },
    {
        path: "/history",
        name: "history",
        component: History,
        props: {
            title: title("История операций"),
        },
    },
    {
        path: "/vendors",
        name: "vendors",
        component: Vendors,
        props: {
            title: title("Поставщики"),
        },
    },
    {
        path: "/objects",
        name: "objects",
        component: Objects,
        props: {
            title: title("Объекты"),
        },
    },
    {
        path: "/payments",
        name: "payments",
        component: Payments,
        props: {
            title: title("Оплата"),
        },
    },
    {
        path: "/materials",
        name: "materials",
        component: Materials,
        props: {
            title: title("Материалы"),
        },
    },
    {
        path: "/material_types",
        name: "material_types",
        component: MaterialTypes,
        props: {
            title: title("Типы материалов"),
        },
    },
    {
        path: "/legal_entities",
        name: "legal_entities",
        component: LegalEntities,
        props: {
            title: title("Юридические лица"),
        },
    },
    {
        path: "/reports",
        name: "reports",
        component: Reports,
        props: {
            title: title("Отчеты"),
        },
    },
    {
        path: "/settings",
        name: "settings",
        component: Settings,
        props: {
            title: title("Настройки приложения"),
        },
    },
    {
        path: "/user_settings",
        name: "user_settings",
        component: UserSettings,
        props: {
            title: title("Настройки пользователя"),
        },
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
