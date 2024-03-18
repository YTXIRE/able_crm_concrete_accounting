import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_all_operations_by_material = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/history-operation/get-all-operations-by-material?token=${
                data.token
            }&vendor_id=${data.vendor_id}&object_id=${data.object_id}&material_id=${data.material_id}&limit=5&offset=${
                data.offset ?? 0
            }&user_id=${localStorage.getItem("user_id")}`
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

export const get_objects_by_vendor = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/history-operation/get-objects-by-vendor?token=${data.token}&vendor_id=${
                data.vendor_id
            }&user_id=${localStorage.getItem("user_id")}`
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

export const get_material_by_object = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/history-operation/get-material-by-object?token=${
                data.token
            }&vendor_id=${data.vendor_id}&object_id=${data.object_id}&user_id=${localStorage.getItem("user_id")}`
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
    const historyOperation = new FormData();
    historyOperation.append("token", data.token);
    historyOperation.append("user_id", Number(localStorage.getItem("user_id")));
    historyOperation.append("vendor_id", data.vendor_id);
    historyOperation.append("material_id", data.material_id);
    historyOperation.append("legal_entity_id", data.legal_entity_id);
    historyOperation.append("object_id", data.object_id);
    historyOperation.append("volume", data.volume);
    historyOperation.append("price", data.price);
    historyOperation.append("total", data.total);
    historyOperation.append("comment", data.comment);
    historyOperation.append("created_at", data.created_at);
    historyOperation.append("is_debt", data.is_debt);
    historyOperation.append("confirmed_data", data.confirmed_data);
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/history-operation/create`, historyOperation)
        .then((d) => {
            if (d?.data?.message) {
                notification("История операций", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("История операций", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update = async (data) => {
    const historyOperation = new FormData();
    historyOperation.append("token", data.token);
    historyOperation.append("user_id", Number(localStorage.getItem("user_id")));
    historyOperation.append("vendor_id", data.vendor_id);
    historyOperation.append("material_id", data.material_id);
    historyOperation.append("legal_entity_id", data.legal_entity_id);
    historyOperation.append("object_id", data.object_id);
    historyOperation.append("volume", data.volume);
    historyOperation.append("price", data.price);
    historyOperation.append("total", data.total);
    historyOperation.append("comment", data.comment);
    historyOperation.append("created_at", data.created_at);
    historyOperation.append("confirmed_data", data.confirmed_data);
    historyOperation.append("id", data.id);
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/history-operation/update`, historyOperation)
        .then((d) => {
            if (d?.data?.message) {
                notification("История операций", "Операция успешно обновлена", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("История операций", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_history_operation = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/history-operation/delete`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: Number(localStorage.getItem("user_id")),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("История операций", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("История операций", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
