import { get_all } from "@/api/units_measurement_volume.js";

export default {
    actions: {
        // eslint-disable-next-line no-empty-pattern
        async getAllUnitsMeasurementVolume({}, payload) {
            return await get_all(payload);
        },
    },
};
