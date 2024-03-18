// import FormData from "form-data";
import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_timezones = async (token) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/time/get-timezones?token=${token}&user_id=${localStorage.getItem(
                "user_id"
            )}`
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

export const get_timezone = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/time/get-timezone?token=${data.token}&timezone_name=${
                data.timezone_name
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

export const set_timezone = async (data) => {
    const time_data = new FormData();
    time_data.append("token", data.token);
    time_data.append("timezone_id", data.timezone_id);
    time_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/time/set-timezone`, time_data)
        .then((d) => {
            if (d?.data?.message) {
                notification("Настройка времени", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Настройка времени", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
