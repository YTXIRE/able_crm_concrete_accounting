import axios from "axios";
import FormData from "form-data";
import { notification } from "../utils/helper";

export const get_debt = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/settings/get-debt?token=${data.token}&user_id=${localStorage.getItem(
                "user_id"
            )}`
        )
        .then((d) => {
            return d?.data?.data?.debt;
        })
        .catch((e) => {
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};

export const save_debt = async (data) => {
    const material_type_data = new FormData();
    material_type_data.append("token", data.token);
    material_type_data.append("active", data.active);
    material_type_data.append("user_id", Number(localStorage.getItem("user_id")));
    return await axios
        .post(`${process.env.VUE_APP_API_URL}/api/settings/set-debt`, material_type_data)
        .then((d) => {
            if (d?.data?.message) {
                notification("Тип материала", d?.data?.message, "success");
                return true;
            }
            return false;
        })
        .catch((e) => {
            notification("Тип материала", e.response.data?.message, "error");
            console.error(e);
            return false;
        })
        .finally(() => {
            return false;
        });
};
