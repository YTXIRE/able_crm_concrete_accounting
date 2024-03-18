import axios from "axios";
import { notification } from "@/utils/helper";

export const get_all = async (token) => {
    return await axios
        .get(
            `${
                process.env.VUE_APP_API_URL
            }/api/units-measurement-volume/get-all?token=${token}&user_id=${localStorage.getItem("user_id")}`
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
