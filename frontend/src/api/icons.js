import axios from "axios";

export const get_all = async (data) => {
    return await axios
        .get(
            `${process.env.VUE_APP_API_URL}/api/icons/get-all?token=${data.token}&limit=48&offset=${
                data.offset ?? 0
            }&user_id=${localStorage.getItem("user_id")}`
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
