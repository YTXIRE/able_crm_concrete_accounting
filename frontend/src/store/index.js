import { createStore } from "vuex";
import auth from "./modules/auth";
import menu from "./modules/menu";
import users from "./modules/users";
import time from "./modules/time";
import files from "./modules/files";
import material_types from "./modules/material_types";
import materials from "./modules/materials";
import legal_entities_types from "./modules/legal_entities_types";
import legal_entities from "./modules/legal_entities";
import icons from "./modules/icons";
import vendors from "./modules/vendors";
import objects from "./modules/objects";
import payments from "./modules/payments";
import history_operation from "@/store/modules/history_operation";
import units_measurement_volume from "@/store/modules/units_measurement_volume";
import settings from "@/store/modules/settings.js";
import dashboard from "@/store/modules/dashboard.js";
import report from "@/store/modules/report.js";

export default createStore({
    modules: {
        auth,
        menu,
        users,
        time,
        files,
        material_types,
        materials,
        legal_entities_types,
        legal_entities,
        icons,
        vendors,
        objects,
        payments,
        history_operation,
        units_measurement_volume,
        settings,
        dashboard,
        report,
    },
});
