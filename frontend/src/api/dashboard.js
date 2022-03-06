import axios from "axios";

export const get_data = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/dashboard/get-data?token=${data.token}&user_id=${localStorage.getItem(
                "user_id"
            )}&period=${data.period}&date_from=${data.date_from}&date_to=${data.date_to}`
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
