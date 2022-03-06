import axios from "axios";

export const get_all = async (token) => {
    return await axios
        .get(
            `${
                process.env.VUE_APP_API_URL
            }/api/legal-entities-types/get-all?token=${token}&user_id=${localStorage.getItem("user_id")}`
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
