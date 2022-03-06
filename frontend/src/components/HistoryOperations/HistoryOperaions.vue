<template>
    <div class="wrapper_history">
        <CreateHistoryOperation
            :materials="materials.materials"
            :materials_types="materials_types.types"
            :objects="objects.objects"
            :vendors="vendors.vendors"
            :legal_entities="legal_entities.legal_entities"
            class="create"
        />
        <el-row v-if="vendors?.vendors?.length" :gutter="24">
            <el-col :span="2">
                <el-card v-loading="loadingMenu" shadow="never">
                    <el-menu class="menu">
                        <el-menu-item
                            v-for="vendor in vendors?.vendors"
                            :key="vendor.id"
                            :index="`${vendor.name}_!-#$te__${vendor.id}`"
                            class="wrapper"
                            @click="set_vendor"
                        >
                            <el-tooltip :content="vendor.name" class="item" effect="dark" placement="left">
                                <font-awesome-icon :icon="[vendor.prefix, vendor.icon]" class="icon" />
                            </el-tooltip>
                        </el-menu-item>
                    </el-menu>
                </el-card>
            </el-col>
            <el-col :span="22">
                <el-card v-loading="loading" class="collapse" shadow="never">
                    <el-collapse
                        v-if="current_vendor && Object.keys(objects_by_vendor).length"
                        :accordion="true"
                        @change="get_object_and_material"
                    >
                        <el-collapse-item v-for="object in Object.keys(objects_by_vendor)" :key="object" :name="object">
                            <template #title>
                                <table class="table_title">
                                    <tr>
                                        <td class="title_object">{{ object }}</td>
                                        <td class="title_total">
                                            {{ format_price(objects_by_vendor[object].total) }}
                                        </td>
                                    </tr>
                                </table>
                            </template>
                            <el-collapse
                                v-if="current_vendor && Object.keys(materials_by_objects).length"
                                :accordion="true"
                                @change="get_object_and_material"
                            >
                                <el-collapse-item
                                    v-for="material in Object.keys(materials_by_objects)"
                                    :key="material"
                                    :name="material"
                                >
                                    <template #title>
                                        <table class="table_title">
                                            <tr>
                                                <td class="title_object">
                                                    <strong class="mt-10">{{ material }}</strong>
                                                </td>
                                                <td class="title_total mt-10">
                                                    {{ format_price(materials_by_objects[material].total) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </template>
                                    <el-table
                                        v-loading="loading"
                                        :data="history_operations?.operations"
                                        :default-sort="{ prop: 'id', order: 'descending' }"
                                        :row-class-name="confirmed_data"
                                        border
                                        empty-text="Данных нет"
                                        style="width: 100%"
                                        @cell-click="click_expand"
                                    >
                                        <el-table-column type="expand">
                                            <template #default="props">
                                                <div class="block_row">
                                                    <div class="block_row_detail">
                                                        <span>ID</span> {{ props.row.id }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Платильщик</span> {{ props.row.legal_entity.legal_entities_type }} {{ props.row.legal_entity.name }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Поставщик</span>
                                                        <span>
                                                            <font-awesome-icon
                                                                :icon="[props.row.vendor.prefix, props.row.vendor.icon]"
                                                                class="vendor_icon"
                                                            />
                                                            <span class="detail_text">
                                                                {{ props.row.vendor.name }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Объект</span>
                                                        {{ props.row.object.name }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Тип материала</span>
                                                        {{ props.row.material.type }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Материал</span>
                                                        {{ props.row.material.name }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>
                                                            Цена за
                                                            {{ word_decimal(props.row.material.units.toLowerCase()) }}
                                                        </span>
                                                        {{ format_price(props.row.price) }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Объем</span>
                                                        {{ format_volume(props.row.volume) }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Итого</span>
                                                        {{ format_price(props.row.total) }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Дата создания</span>
                                                        {{ format_date(props.row.created_at) }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Подтверждение</span>
                                                        {{
                                                            Number(props.row.confirmed_data) === 0
                                                                ? "Данные не подтверждены"
                                                                : "Данные подтверждены"
                                                        }}
                                                    </div>
                                                    <div class="block_row_detail">
                                                        <span>Документы</span>
                                                        <a
                                                            v-if="props.row.file.link"
                                                            :href="props.row.file.link"
                                                            target="_blank"
                                                        >
                                                            {{ props.row.file.name }}
                                                        </a>
                                                        <span v-else>Документ не загружен</span>
                                                    </div>
                                                    <div class="block_row_detail" v-if="props.row.comment">
                                                        <span>Комментарий</span>
                                                        <span class="detail_text comment">{{ props.row.comment }}</span>
                                                    </div>
                                                    <br />
                                                </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="ID" prop="id" sortable width="80" />
                                        <el-table-column label="Тип материала" sortable width="150">
                                            <template #default="scope">
                                                {{ scope.row.material.type }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Материал" sortable>
                                            <template #default="scope">
                                                {{ scope.row.material.name }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Дата создания" sortable width="170">
                                            <template #default="scope">
                                                {{ format_date(scope.row.created_at) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Цена за тонну" sortable>
                                            <template #default="scope">
                                                {{ format_price(scope.row.price) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Объем" sortable>
                                            <template #default="scope">
                                                {{ format_volume(scope.row.volume) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Итого" sortable>
                                            <template #default="scope">
                                                {{ format_price(scope.row.total) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Действие">
                                            <template #default="scope">
                                                <EditHistoryOperation
                                                    :data="scope.row"
                                                    :materials="materials.materials"
                                                    :materials_types="materials_types.types"
                                                    :objects="objects.objects"
                                                    :vendors="vendors.vendors"
                                                    :legal_entities="legal_entities.legal_entities"
                                                    class="block"
                                                />
                                                <el-popconfirm
                                                    cancel-button-text="Стой! Оставь"
                                                    confirm-button-text="Естественно"
                                                    icon-color="red"
                                                    title="Вы точно хотите удалить?"
                                                    @confirm="remove(scope.row.id)"
                                                >
                                                    <template #reference>
                                                        <el-button class="remove" type="danger">
                                                            <font-awesome-icon icon="trash" />
                                                        </el-button>
                                                    </template>
                                                </el-popconfirm>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <br />
                                    <el-pagination
                                        :page-size="5"
                                        :total="Number(history_operations.count)"
                                        background
                                        class="pagination"
                                        hide-on-single-page
                                        layout="prev, pager, next"
                                        @next-click="get_operations"
                                        @prev-click="get_operations"
                                        @current-change="get_operations"
                                    ></el-pagination>
                                </el-collapse-item>
                            </el-collapse>
                        </el-collapse-item>
                    </el-collapse>
                    <div v-else>Нет операций</div>
                </el-card>
            </el-col>
        </el-row>
        <el-card v-else v-loading="loadingMenu">
            <div>Поставщики не добавлены</div>
        </el-card>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { formatDate, formatNumber, formatVolume, getElementByXpath, wordDecline } from "@/utils/helper.js";
import CreateHistoryOperation from "@/components/HistoryOperations/CreateHistoryOperation.vue";
import EditHistoryOperation from "@/components/HistoryOperations/EditHistoryOperation.vue";

export default {
    components: { CreateHistoryOperation, EditHistoryOperation },
    data() {
        return {
            loading: false,
            activeNames: [],
            current_vendor: "",
            current_vendor_id: 1,
            loadingMenu: false,
            history_operations: {
                vendor: [],
                operations: [],
            },
            vendors: [],
            materials: [],
            legal_entities: [],
            materials_types: [],
            objects: [],
            active_tab: "0",
            objects_by_vendor: [],
            materials_by_objects: [],
            current_object_id: 0,
            current_material_id: 0,
            offset: 0,
        };
    },
    methods: {
        ...mapActions([
            "getAllHistoryOperation",
            "getAllMaterials",
            "getAllVendors",
            "getAllMaterialTypes",
            "getAllObjects",
            "deleteHistoryOperation",
            "getAllObjectsByVendorHistoryOperation",
            "getAllMaterialsByObjectsHistoryOperation",
            "getAllLegalEntities"
        ]),
        set_vendor: async function (e) {
            const vendor = e.index.split("_!-#$te__");
            this.current_vendor = vendor[0];
            this.current_vendor_id = vendor[1];
            this.objects_by_vendor = [];
            this.materials_by_objects = [];
            this.current_object_id = 0;
            this.current_material_id = 0;
            this.offset = 0;
            this.loading = true;
            this.objects_by_vendor = await this.getAllObjectsByVendorHistoryOperation({
                token: localStorage.getItem("crm_token"),
                vendor_id: vendor[1],
            });
            this.loading = false;
        },
        format_price: function (price) {
            return formatNumber(price);
        },
        format_date: function (date) {
            return formatDate(date);
        },
        format_volume: function (data) {
            return formatVolume(data);
        },
        word_decimal: function (word) {
            return wordDecline(word);
        },
        click_expand: function (data) {
            const path = getElementByXpath(
                `//tr[contains(@class, "el-table__row")]//div[text() = "${data.id}"]/../..//div[@class="cell"]/div[contains(@class, "el-table__expand-icon")]`
            );
            let thisHeading = path.iterateNext();
            while (thisHeading) {
                thisHeading.click();
                thisHeading = path.iterateNext();
            }
        },
        remove: function (id) {
            this.deleteHistoryOperation({
                token: localStorage.getItem("crm_token"),
                id,
            });
        },
        get_object_and_material: async function (name) {
            this.loading = true;
            if (Object.keys(this.objects_by_vendor).includes(name)) {
                this.current_object_id = this.objects_by_vendor[name].object_id;
                this.materials_by_objects = await this.getAllMaterialsByObjectsHistoryOperation({
                    token: localStorage.getItem("crm_token"),
                    vendor_id: this.current_vendor_id,
                    object_id: this.current_object_id,
                });
            }
            if (Object.keys(this.materials_by_objects).includes(name)) {
                this.current_material_id = this.materials_by_objects[name].material_id;
                this.history_operations = await this.getAllHistoryOperation({
                    token: localStorage.getItem("crm_token"),
                    vendor_id: this.current_vendor_id,
                    object_id: this.current_object_id,
                    material_id: this.current_material_id,
                    offset: this.offset,
                });
            }
            this.loading = false;
        },
        get_operations: async function (e) {
            this.loading = true;
            this.offset = e * 5 - 5;
            this.history_operations = await this.getAllHistoryOperation({
                token: localStorage.getItem("crm_token"),
                vendor_id: this.current_vendor_id,
                object_id: this.current_object_id,
                material_id: this.current_material_id,
                offset: this.offset,
            });
            this.loading = false;
        },
        confirmed_data({ row }) {
            if (Number(row.confirmed_data) === 0) {
                return "danger_docs";
            }
            return "";
        },
    },
    async mounted() {
        this.loading = true;
        this.loadingMenu = true;
        this.materials = await this.getAllMaterials({
            token: localStorage.getItem("crm_token"),
            offset: 0,
            limit: 0,
        });
        this.vendors = await this.getAllVendors({
            token: localStorage.getItem("crm_token"),
            offset: 0,
            limit: 0,
            archive: 0,
        });
        this.materials_types = await this.getAllMaterialTypes({
            token: localStorage.getItem("crm_token"),
        });
        this.legal_entities = await this.getAllLegalEntities({
            token: localStorage.getItem("crm_token"),
        });
        this.objects = await this.getAllObjects({
            token: localStorage.getItem("crm_token"),
            offset: 0,
            limit: 0,
            archive: 0,
        });
        this.loading = false;
        this.loadingMenu = false;
    },
    computed: {
        ...mapGetters(["getIsNewHistoryOperation"]),
    },
    watch: {
        getIsNewHistoryOperation: async function () {
            this.loading = true;
            if (this.current_vendor_id) {
                this.objects_by_vendor = await this.getAllObjectsByVendorHistoryOperation({
                    token: localStorage.getItem("crm_token"),
                    vendor_id: this.current_vendor_id,
                });
            }
            if (this.current_object_id) {
                this.materials_by_objects = await this.getAllMaterialsByObjectsHistoryOperation({
                    token: localStorage.getItem("crm_token"),
                    vendor_id: this.current_vendor_id,
                    object_id: this.current_object_id,
                });
            }
            if (this.current_material_id) {
                this.history_operations = await this.getAllHistoryOperation({
                    token: localStorage.getItem("crm_token"),
                    vendor_id: this.current_vendor_id,
                    object_id: this.current_object_id,
                    material_id: this.current_material_id,
                    offset: this.offset,
                });
            }
            this.loading = false;
        },
    },
};
</script>

<style scoped>
.title {
    text-align: left;
    position: relative;
}

.icon {
    font-size: 20px !important;
    margin-left: -5px;
}

.text {
    margin-left: 10px;
}

.menu {
    text-align: left;
}

.wrapper_history {
    position: relative;
    margin: auto;
    width: 1400px !important;
}

.el-menu {
    border: none;
    text-align: left;
}

.create {
    position: absolute;
    right: -50px;
}

.item {
    font-size: 20px;
    outline: none;
}

.block_row {
    width: 1000px;
    display: block;
    margin: auto;
}

.block_row span {
    font-weight: bold;
}

.el-table__row {
    cursor: pointer !important;
}

.el-table__expand-icon {
    dispay: none !important;
}

.block_row_detail {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #cfcfcf;
    margin-top: 8px;
    padding-top: 8px;
}

.detail_text {
    margin-left: 10px;
    font-weight: 300 !important;
}

.comment {
    white-space: pre-line;
    display: inline;
    word-break: break-word;
    max-width: 70%;
}

.title_object {
    text-align: left;
    width: 60%;
}

.title_total {
    color: green;
    font-weight: bold;
    text-align: left;
    width: 40%;
}

.table_title {
    width: 100%;
}

.title_material_type {
    color: green !important;
    font-weight: bold !important;
}

.vendor_icon {
    font-size: 12px;
    margin-right: -7px;
}

.collapse {
    min-width: 30px !important;
}

.block {
    display: inline;
}

.remove {
    margin-left: 10px;
}

.mt-10 {
    padding-left: 10px !important;
}
</style>
