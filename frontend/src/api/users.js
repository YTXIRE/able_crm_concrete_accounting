import axios from "axios";
import { notification } from "../utils/helper";
import FormData from "form-data";

export const get_info = async (token) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/users/get-info?token=${token}&user_id=${localStorage.getItem(
                "user_id"
            )}`
        )
        .then((d) => {
            if (d?.data?.data) {
                localStorage.setItem("user_data", JSON.stringify(d?.data?.data));
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Функционал пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const update_info = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/users/update-info`, {
            id: data.id,
            login: data.login,
            email: data.email,
            token: data.token,
            password: data.password,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.data) {
                notification("Функционал пользователя", "Данные успешно обновленны", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Функционал пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const change_password = async (data) => {
    return await axios
        .put(`${process.env.VUE_APP_API_URL}/api/users/change-password`, {
            id: data.id,
            password: data.password,
            token: data.token,
            user_id: Number(localStorage.getItem("user_id")),
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Функционал пользователя", d.data.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Функционал пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const upload_avatar = async (data) => {
    const upload_data = new FormData();
    upload_data.append("id", data.id);
    upload_data.append("token", data.token);
    upload_data.append("avatar", data.avatar);
    upload_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/files/upload-avatar`, upload_data)
        .then(async (d) => {
            if (d?.data?.data?.avatar) {
                notification("Функционал пользователя", "Аватар успешно загружен", "success");
                await get_info(data.token);
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Функционал пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const get_all = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/users/get-all?token=${data.token}&limit=${data.limit ?? 0}&offset=${
                data.offset ?? 0
            }&user_id=${localStorage.getItem("user_id")}`
        )
        .then((d) => {
            return d?.data?.data;
        })
        .catch((e) => {
            notification("Функционал пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const create = async (data) => {
    const user_data = new FormData();
    user_data.append("token", data.token);
    user_data.append("login", data.login);
    user_data.append("email", data.email);
    user_data.append("password", data.password);
    user_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/users/create`, user_data)
        .then((d) => {
            if (d?.data?.data) {
                notification("Создание пользоваля", "Пользователь успешно создан", "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Создание пользоваля", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const delete_user = async (data) => {
    return await axios
        .delete(`${process.env.VUE_APP_API_URL}/api/users/delete`, {
            data: {
                token: data.token,
                id: data.id,
                user_id: Number(localStorage.getItem("user_id")),
            },
        })
        .then((d) => {
            if (d?.data?.message) {
                notification("Удаление пользователя", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Удаление пользователя", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
