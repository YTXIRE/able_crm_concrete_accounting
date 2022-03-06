import { get_all } from "@/api/legal_entities_types.js";

export default {
    actions: {
        // eslint-disable-next-line no-empty-pattern
        async getAllLegalEntitiesTypes({}, payload) {
            return await get_all(payload);
        },
    },
};
