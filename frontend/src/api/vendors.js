import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_all = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/vendors/get-all?token=${data.token}&limit=${data.limit ?? 0}&offset=${
                data.offset ?? 0
            }&archive=${data.archive ?? 0}&user_id=${localStorage.getItem("user_id")}`
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

export const save = async (data) => {
    const material_type_data = new FormData();
    material_type_data.append("token", data.token);
    material_type_data.append("name", data.name);
    material_type_data.append("icon_id", data.icon_id);
    material_type_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/vendors/create`, material_type_data)
        .then((d) => {
            if (d?.data?.message) {
                notification("Поставщики", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Поставщики", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/vendors/update`, {
            id: data.id,
            name: data.name,
            token: data.token,
            icon_id: data.icon_id,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.data) {
                notification("Поставщики", "Поставщик успешно обновлен", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Поставщики", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_vendor = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/vendors/delete`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: Number(localStorage.getItem("user_id")),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Постащики", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Постащики", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const restore = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/vendors/restore`, {
            token: data.token,
            id: data.id,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Постащики", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Постащики", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
