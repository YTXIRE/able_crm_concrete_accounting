import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_all = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/legal-entities/get-all?token=${data.token}&limit=${
                data.limit ?? 0
            }&offset=${data.offset ?? 0}&archive=${data.archive ?? 0}&user_id=${localStorage.getItem("user_id")}`
        )
        .then((d) => {
            return d?.data?.data;
        })
        .catch((e) => {
            if ([404, 400].includes(e.response.data.code)) {
                notification("Отсутствует авторизация", e.response.data.message, "error");
                localStorage.removeItem("crm_token");
                setTimeout(() => {
                    location.reload();
                }, 3000)
            }
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const save = async (data) => {
    const legal_entity_data = new FormData();
    legal_entity_data.append("token", data.token);
    legal_entity_data.append("name", data.name);
    legal_entity_data.append("legal_entities_type_id", data.legal_entities_type_id);
    legal_entity_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/legal-entities/create`, legal_entity_data)
        .then((d) => {
            if (d?.data?.message) {
                notification("Юридические лица", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Юридические лица", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/legal-entities/update`, {
            id: data.id,
            name: data.name,
            token: data.token,
            legal_entities_type_id: data.legal_entities_type_id,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.data) {
                notification("Юридические лица", "Организация успешно обновлена", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Юридические лица", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_legal_entity = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/legal-entities/delete`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: Number(localStorage.getItem("user_id")),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Юридические лица", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Юридические лица", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const restore = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/legal-entities/restore`, {
            token: data.token,
            id: data.id,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Юридические лица", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Юридические лица", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
