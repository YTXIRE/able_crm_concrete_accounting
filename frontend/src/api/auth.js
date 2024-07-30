import axios from "axios";
import FormData from "form-data";
import { notification } from "@/utils/helper";

export const login = async (data) => {
    const auth_data = new FormData();
    auth_data.append("login", data.login);
    auth_data.append("password", data.password);
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/auth/login`, auth_data)
        .then((d) => {
            if (d?.data?.data?.token) {
                localStorage.setItem("crm_token", d?.data?.data?.token);
                localStorage.setItem("user_id", d?.data?.data?.id);
                if (d?.data?.data?.is_demo === undefined) {
                    localStorage.setItem("is_demo", "1");
                } else {
                    localStorage.setItem("is_demo", d?.data?.data?.is_demo);
                }
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Авторизация", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const logout = async (token) => {
    const data = new FormData();
    data.append("token", token);
    data.append("user_id", localStorage.getItem("user_id"));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/auth/logout`, data)
        .then((d) => {
            notification("Выход из системы", d?.data?.message, "success");
            localStorage.removeItem("crm_token");
            localStorage.removeItem("user_data");
            localStorage.removeItem("user_id");
            localStorage.removeItem("currentMenu");
            localStorage.removeItem("currentLink");
            localStorage.removeItem("is_demo");
            return true;
        })
        .catch((e) => {
            notification("Выход из системы", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
