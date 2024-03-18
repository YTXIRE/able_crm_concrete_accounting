import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_all = async (data) => {
    let offset = {};

    for (let item in data.offset) {
        offset[item] = data.offset[item];
    }
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/payments/get-all?token=${data.token}&limit=10&offset=${JSON.stringify(
                offset
            )}&user_id=${localStorage.getItem("user_id")}`
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
    const payments_data = new FormData();
    payments_data.append("token", data.token);
    payments_data.append("vendor_id", data.vendor_id);
    payments_data.append("legal_entity_id", data.legal_entity_id);
    payments_data.append("material_type_id", data.material_type_id);
    payments_data.append("amount", data.amount);
    payments_data.append("created_at", data.created_at);
    payments_data.append("operation_type", data.operation_type);
    payments_data.append("user_id", localStorage.getItem("user_id"));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/payments/create`, payments_data)
        .then((d) => {
            if (d?.data?.message) {
                notification("Платеж", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Платеж", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/payments/update`, {
            id: data.id,
            vendor_id: data.vendor_id,
            token: data.token,
            legal_entity_id: data.legal_entity_id,
            material_type_id: data.material_type_id,
            amount: data.amount,
            operation_type: data.operation_type,
            created_at: data.created_at,
            user_id: localStorage.getItem("user_id"),
        })
        .then((d) => {
            if (d?.data?.data) {
                notification("Платеж", "Платеж успешно обновлен", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Платеж", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_payment = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/payments/delete`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: localStorage.getItem("user_id"),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Платеж", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Платеж", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
