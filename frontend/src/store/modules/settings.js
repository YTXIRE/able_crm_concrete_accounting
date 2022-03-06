import { get_debt, save_debt } from "@/api/settings.js";

export default {
    actions: {
        // eslint-disable-next-line no-empty-pattern
        async getDebt({}, payload) {
            return await get_debt(payload);
        },
        // eslint-disable-next-line no-empty-pattern
        async saveDebt({}, payload) {
            const result = await save_debt(payload);
            if (result) {
                return result;
            }
            return false;
        },
    },
};
