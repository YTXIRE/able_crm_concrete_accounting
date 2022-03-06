<template>
    <div>
        <div class="button-new-tag"></div>
        <div class="filters">
            <div class="generate">
                <export-excel :data="export_excel_vendor" :fields="excel_fields" :name="get_excel_name" class="excel">
                    <el-button :disabled="export_excel_vendor.length === 0" type="success"> Сохранить в Excel
                    </el-button>
                </export-excel>
                <el-button :disabled="filtered_vendors.length === 0" type="danger" @click="createPDF">
                    Сохранить в PDF
                </el-button>
                <el-button v-print="printObj" :disabled="filtered_vendors.length === 0" type="info"> Печать</el-button>
            </div>
        </div>
        <el-divider />
        <div v-loading="loading" class="content_wrapper">
            <div class="formated_reports">
                <div v-if="vendors.length">
                    <div v-for="vendor in vendors" :key="vendor.id" class="vendor_item">
                        <el-checkbox v-model="vendor.visible" :label="vendor.name" border />
                    </div>
                </div>
                <p v-else>Нет данных</p>
            </div>
            <div id="print_base_vendors" class="view_reports">
                <el-table :data="filtered_vendors" style="width: 100%" >
                    <el-table-column label="Поставщик" prop="name"/>
                    <el-table-column label="Взяли">
                        <template #default="scope">
                            <table
                                v-for="item in scope.row.total"
                                :key="item"
                                class="debt_data"
                            >
                                <tr v-for="value in item.take" :key="value">
                                    <td>
                                        {{ value.legal_entity }}:
                                        <span class="green">{{ value.total }}</span>
                                    </td>
                                </tr>
                            </table>
                        </template>
                    </el-table-column>
                    <el-table-column label="Долг ">
                        <template #default="scope">
                            <table
                                v-for="item in scope.row.total"
                                :key="item"
                                class="debt_data"
                            >
                                <tr v-for="value in item.debt" :key="value">
                                    <td>
                                        {{ value.legal_entity }}:
                                        <span :class="[String(value.total).startsWith('-') ? 'blue' : 'red']">
                                        {{ value.total }}
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </div>
</template>

<script>
import { formatDate, formatNumber } from "@/utils/helper.js";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import { mapActions } from "vuex";

pdfMake.vfs = pdfFonts.pdfMake.vfs;

export default {
    data() {
        return {
            vendors: [],
            loading: false,
            export_excel_vendor: [],
            printObj: {
                id: "print_base_vendors",
                popTitle: "ABLE CRM"
            }
        };
    },
    methods: {
        ...mapActions(["getBaseReport"]),
        format_price(price) {
            return formatNumber(price);
        },
        createPDF() {
            const rows = [];
            for (let key in this.export_excel_vendor) {
                rows.push([
                    this.export_excel_vendor[key].vendor,
                    this.export_excel_vendor[key].take,
                    this.export_excel_vendor[key].debt
                ]);
            }
            const docDefinition = {
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
                        layout: "lightHorizontalLines",
                        table: {
                            headerRows: 1,
                            widths: ["*", "auto", "auto"],
                            body: [["Поставщик", "Взяли", "Долг"], ...rows]
                        }
                    }
                ]
            };
            pdfMake.createPdf(docDefinition).download(this.get_pdf_name);
        }
    },
    async mounted() {
        const base_report = await this.getBaseReport(localStorage.getItem("crm_token"));
        for (let item in base_report) {
            let operaions = [];
            for (let key in base_report[item]) {
                let take = [];
                let debt = [];
                for (let value in base_report[item][key]) {
                    if (key === "Взяли") {
                        take.push({
                            legal_entity: value,
                            total: this.format_price(base_report[item][key][value])
                        });
                    }
                    if (key === "Долг") {
                        debt.push({
                            legal_entity: value,
                            total: this.format_price(base_report[item][key][value])
                        });
                    }
                }
                operaions.push({ take, debt });
            }
            this.vendors.push({
                name: item,
                total: operaions,
                visible: true
            });
        }
    },
    computed: {
        filtered_vendors() {
            const vendors = this.vendors.filter((vendor) => vendor.visible);
            let filtered_vendors = [];
            for (let key in vendors) {
                let take = [];
                let debt = [];
                for (let item in vendors[key].total) {
                    if (vendors[key].total[item].take.length) {
                        for (let i in vendors[key].total[item].take) {
                            const enter = +i + 1 === vendors[key].total[item].take.length ? "" : "\n";
                            take.push(`${vendors[key].total[item].take[i].legal_entity}: ${vendors[key].total[item].take[i].total}${enter}`);
                        }
                    }
                    if (vendors[key].total[item].debt.length) {
                        for (let i in vendors[key].total[item].debt) {
                            const enter = +i + 1 === vendors[key].total[item].debt.length ? "" : "\n";
                            debt.push(`${vendors[key].total[item].debt[i].legal_entity}: ${vendors[key].total[item].debt[i].total}${enter}`);
                        }
                    }
                }
                filtered_vendors.push({
                    vendor: vendors[key].name,
                    take: take.join(""),
                    debt: debt.join("")
                });
            }
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.export_excel_vendor = filtered_vendors;
            return vendors;
        },
        get_excel_name() {
            const date = new Date().getTime() / 1000;
            return `Базовая выгрузка от ${formatDate(date)}.xls`;
        },
        get_pdf_name() {
            const date = new Date().getTime() / 1000;
            return `Базовая выгрузка от ${formatDate(date)}.pdf`;
        },
        excel_fields() {
            return {
                "Поставщик": "vendor",
                "Взяли": "take",
                "Долг": "debt"
            };
        }
    }
};
</script>

<style scoped>
.filters {
    position: relative;
    text-align: left;
    width: 1400px !important;
}

.button-new-tag {
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
}

.content_wrapper {
    display: flex;
}

.formated_reports {
    border-right: 1px solid #dcdfe6;
    width: 400px;
    justify-content: left;
}

.view_reports {
    width: 1000px;
    padding-left: 10px;
}

.generate {
    position: absolute !important;
    right: 30px;
    margin-top: -35px;
    display: flex;
}

.vendor_item {
    margin-bottom: 10px;
}

.vendor_item .el-checkbox {
    width: 97%;
}

.excel {
    margin-right: 10px;
}

.red {
    color: red;
    font-weight: 700;
}

.blue {
    color: #2b2bde;
    font-weight: 700;
}

.green {
    color: green;
    font-weight: 700;
}
</style>
