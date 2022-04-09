<template>
    <div>
        <div v-loading="loadingFilters" class="filters">
            <Filters v-if="filters_data.vendor.length" :filters_data_new="filters_data" />
            <div v-if="filters.length && filters_data.vendor.length" class="generate">
                <export-excel :data="formated_date" :fields="excel_fields" :name="get_excel_name" class="excel">
                    <el-button :disabled="filtered_data.length === 0" type="success"> Сохранить в Excel</el-button>
                </export-excel>
                <el-button :disabled="filtered_data.length === 0" type="danger" @click="createPDF">
                    Сохранить в PDF
                </el-button>
                <el-button v-print="printObj" :disabled="filtered_data.length === 0" type="info"> Печать</el-button>
            </div>
        </div>
        <el-divider class="first_line" />
        <div v-loading="loadingFilters">
            <div v-if="filters.length && filters_data.vendor.length" class="wrapper_filter">
                <el-card
                    v-for="filter in filters"
                    :key="filter.id"
                    class="card"
                    shadow="hover"
                    @click.stop.prevent="get_filter(filter.id)"
                >
                    <div>
                        {{ filter.name }}
                        <span class="icon">
                            <EditFilters :data="filter" :filters_data_edit="filters_data" />
                            <el-popconfirm
                                cancel-button-text="Стой! Оставь"
                                confirm-button-text="Естественно"
                                icon-color="red"
                                title="Вы точно хотите удалить?"
                                @confirm="remove_filter(filter.id)"
                            >
                                <template #reference>
                                    <el-button circle plain type="danger">
                                        <font-awesome-icon icon="trash" />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </span>
                    </div>
                </el-card>
            </div>
            <div v-else>Нет созданных фильтров</div>
        </div>
        <el-divider />
        <div id="print_advanced_vendors">
            <div v-loading="loading">
                <div v-if="filtered_data.length">
                    <el-table
                        v-loading="loading"
                        :data="filtered_data"
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
                                    <div class="block_row_detail"><span>ID</span> {{ props.row.id }}</div>
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
                                            props.row.confirmed_data === 0
                                                ? "Данные не подтверждены"
                                                : "Данные подтверждены"
                                        }}
                                    </div>
                                    <div class="block_row_detail">
                                        <span>Документы</span>
                                        <a v-if="props.row.file.link" :href="props.row.file.link" target="_blank">
                                            {{ props.row.file.name }}
                                        </a>
                                        <span v-else>Документ не загружен</span>
                                    </div>
                                    <div class="block_row_detail">
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
                    </el-table>
                </div>
                <div v-else>Ни выбран не один фильтр. Пожалуйста, выберите фильтр</div>
            </div>
            <el-divider />
            <div v-if="total_summary.total || total_summary.volume">
                <el-table
                    v-loading="loading"
                    :data="[total_summary]"
                    empty-text="Данных нет"
                    style="width: 100%"
                >
                    <el-table-column prop="id" width="80" />
                    <el-table-column width="190" />
                    <el-table-column />
                    <el-table-column width="200" />
                    <el-table-column />
                    <el-table-column label="Объем">
                        <template #default="scope">
                            {{ format_volume(scope.row.volume) }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Итого">
                        <template #default="scope">
                            {{ format_price(scope.row.total) }}
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
import Filters from "@/components/Reports/Filters.vue";
import { mapActions, mapGetters } from "vuex";
import { formatDate, formatNumber, formatVolume, getElementByXpath, wordDecline } from "@/utils/helper.js";
import EditFilters from "@/components/Reports/EditFilters.vue";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";

pdfMake.vfs = pdfFonts.pdfMake.vfs;

export default {
    components: { EditFilters, Filters },
    data() {
        return {
            filters: [],
            filtered_data: [],
            current_filter: 0,
            loading: false,
            loadingFilters: false,
            printObj: {
                id: "print_advanced_vendors",
                popTitle: "ABLE CRM"
            },
            filters_data: {
                vendor: [],
                object: [],
                legal_entity: [],
                material: [],
                material_type: []
            },
            total_summary: {
                total: 0,
                volume: 0
            }
        };
    },
    methods: {
        ...mapActions(["getFilters", "getAdvancedReport", "deleteFilter", "getAllVendors", "getAllObjects",
            "getAllLegalEntities", "getAllMaterials", "getAllMaterialTypes"]),
        remove_filter: async function(id) {
            await this.deleteFilter({
                token: localStorage.getItem("crm_token"),
                id
            });
            if (this.current_filter === id) {
                this.filtered_data = [];
                this.total_summary = {
                    total: 0,
                    volume: 0
                };
            }
        },
        get_filter: async function(id) {
            this.loading = true;
            this.current_filter = id;
            const filter = this.filters.filter((filter) => filter.id === id)[0]["filters"];
            const response = await this.getAdvancedReport({
                token: localStorage.getItem("crm_token"),
                filters: filter
            });
            this.total_summary = {
                total: 0,
                volume: 0
            };
            if (response) {
                this.filtered_data = response;
            } else {
                this.loading = false;
            }
            this.filtered_data.forEach(data => {
                this.total_summary.total += data.total;
                this.total_summary.volume += data.volume;
            });
            this.loading = false;
        },
        get_filters: async function() {
            this.loadingFilters = true;
            this.loading = true;
            this.filters = await this.getFilters(localStorage.getItem("crm_token"));
            this.loading = false;
            this.loadingFilters = false;
        },
        confirmed_data({ row }) {
            if (row.confirmed_data === 0) {
                return "danger_docs";
            }
            return "";
        },
        click_expand: function(data) {
            const path = getElementByXpath(
                `//tr[contains(@class, "el-table__row")]//div[text() = "${data.id}"]/../..//div[@class="cell"]/div[contains(@class, "el-table__expand-icon")]`
            );
            let thisHeading = path.iterateNext();
            while (thisHeading) {
                thisHeading.click();
                thisHeading = path.iterateNext();
            }
        },
        format_price: function(price) {
            return formatNumber(price);
        },
        format_date: function(date) {
            return formatDate(date);
        },
        format_volume: function(data) {
            return formatVolume(data);
        },
        word_decimal: function(word) {
            return wordDecline(word);
        },
        createPDF() {
            let data = [];
            this.filtered_data.forEach((item) => {
                data.push([
                    item.id,
                    item.vendor.name,
                    item.object.name,
                    item.material.type,
                    item.material.name,
                    this.format_price(item.price),
                    this.format_volume(item.volume),
                    this.format_price(item.total),
                    this.format_date(item.created_at),
                    item.confirmed_data === 0 ? "Данные не подтверждены" : "Данные подтверждены",
                    item.file.link === null ? "Документ не загружен" : item.file.link,
                    item.comment
                ]);
            });
            const docDefinition = {
                pageSize: "A4",
                pageOrientation: "landscape",
                defaultStyle: {
                    fontSize: 10
                },
                content: [
                    {
                        text: "Create by ABLE CRM"
                    },
                    {
                        text: " "
                    },
                    {
                        text: " "
                    },
                    {
                        table: {
                            headerRows: 1,
                            widths: [
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto",
                                "auto"
                            ],
                            body: [
                                [
                                    "ID",
                                    "Поставщик",
                                    "Объект",
                                    "Тип материала",
                                    "Материал",
                                    "Стоимость",
                                    "Объем",
                                    "Итого",
                                    "Дата создания",
                                    "Подтверждение",
                                    "Документы",
                                    "Комментарий"
                                ],
                                ...data
                            ]
                        }
                    },
                    {
                        text: " "
                    },
                    {
                        text: `Сумарный объем: ${this.format_volume(this.total_summary.volume)}`
                    },
                    {
                        text: " "
                    },
                    {
                        text: `Сумарная стоимость: ${this.format_price(this.total_summary.total)}`
                    }
                ]
            };
            pdfMake.createPdf(docDefinition).download(this.get_pdf_name);
        }
    },
    async mounted() {
        await this.get_filters();
        this.loadingFilters = true;
        let tmp = {};
        let data = await this.getAllVendors({ token: localStorage.getItem("crm_token") });
        tmp.vendor = data.vendors;
        data = await this.getAllObjects({ token: localStorage.getItem("crm_token") });
        tmp.object = data.objects;
        data = await this.getAllLegalEntities({ token: localStorage.getItem("crm_token") });
        tmp.legal_entity = data.legal_entities;
        data = await this.getAllMaterials({ token: localStorage.getItem("crm_token") });
        tmp.material = data.materials;
        data = await this.getAllMaterialTypes({ token: localStorage.getItem("crm_token") });
        tmp.material_type = data.types;
        this.filters_data = tmp;
        this.loadingFilters = false;
    },
    computed: {
        ...mapGetters(["getIsNewFilter"]),
        get_excel_name() {
            const date = new Date().getTime() / 1000;
            return `Расширенная выгрузка от ${formatDate(date)}.xls`;
        },
        get_pdf_name() {
            const date = new Date().getTime() / 1000;
            return `Расширенная выгрузка от ${formatDate(date)}.pdf`;
        },
        excel_fields() {
            return {
                "ID": "id",
                "Поставщик": "vendor",
                "Объект": "object",
                "Тип материала": "material_type",
                "Материал": "material",
                "Стоимость": "price",
                "Объем": "volume",
                "Итого": "total",
                "Дата создания": "created_at",
                "Подтверждение": "confirmed_data",
                "Документы": "document",
                "Комментарий": "comment"
            };
        },
        formated_date() {
            let data = [];
            this.filtered_data.forEach((item) => {
                data.push({
                    id: item.id,
                    vendor: item.vendor.name,
                    object: item.object.name,
                    material_type: item.material.type,
                    material: item.material.name,
                    price: this.format_price(item.price),
                    volume: this.format_volume(item.volume),
                    total: this.format_price(item.total),
                    created_at: this.format_date(item.created_at),
                    confirmed_data: item.confirmed_data === 0 ? "Данные не подтверждены" : "Данные подтверждены",
                    document: item.file.link === null ? "Документ не загружен" : item.file.link,
                    comment: item.comment
                });
            });
            data.push({});
            data.push({});
            data.push({ "id": `Сумарный объем: ${this.format_volume(this.total_summary.volume)}` });
            data.push({});
            data.push({ "id": `Сумарная стоимость: ${this.format_price(this.total_summary.total)}` });
            return data;
        }
    },
    watch: {
        getIsNewFilter: function() {
            this.get_filters();
        }
    }
};
</script>

<style scoped>
.filters {
    position: relative;
    text-align: left;
    width: 1400px !important;
    height: 40px !important;
}

.generate {
    position: absolute !important;
    right: 30px;
    margin-top: -43px;
    height: 40px !important;
    display: flex;
}

.first_line {
    margin-top: 16px;
}

.card {
    margin: 10px;
    position: relative;
    cursor: pointer;
}

.wrapper_filter {
    display: flex;
    flex-wrap: wrap;
}

.icon {
    margin-left: 10px;
    font-size: 10px;
}

.block_row {
    width: 1000px;
    display: block;
    margin: auto;
}

.block_row span {
    font-weight: bold;
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

.vendor_icon {
    font-size: 12px;
    margin-right: -7px;
}

.excel {
    margin-right: 10px;
}
</style>
