import axios from "axios";
import FormData from "form-data";
import { notification } from "@/utils/helper.js";

export const get_base = async (token) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/report/get-base?token=${token}&user_id=${localStorage.getItem(
                "user_id"
            )}`
        )
        .then((d) => {
            return d?.data?.data;
        })
        .catch((e) => {
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const get_advanced = async (data) => {
    const report = new FormData();
    report.append("token", data.token);
    report.append("user_id", Number(localStorage.getItem("user_id")));
    report.append("filters", JSON.stringify(data.filters));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/report/get-advanced`, report)
        .then((d) => {
            return d?.data?.data;
        })
        .catch((e) => {
            notification("Расширенный отчет", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const save_filter = async (data) => {
    const filter = new FormData();
    filter.append("token", data.token);
    filter.append("user_id", Number(localStorage.getItem("user_id")));
    filter.append("name", data.name);
    filter.append("filters", JSON.stringify(data.filters));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/report/create-filter`, filter)
        .then((d) => {
            if (d?.data?.message) {
                notification("Расширенный отчет", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Расширенный отчет", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const get_filters = async (token) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/report/get-filters?token=${token}&user_id=${localStorage.getItem(
                "user_id"
            )}`
        )
        .then((d) => {
            return d?.data?.data;
        })
        .catch((e) => {
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_filter = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/report/delete-filter`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: Number(localStorage.getItem("user_id")),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Расширенный отчет", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Расширенный отчет", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/report/update-filter`, {
            token: data.token,
            user_id: Number(localStorage.getItem("user_id")),
            name: data.name,
            filters: data.filters,
            id: data.id,
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Расширенный отчет", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Расширенный отчет", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
