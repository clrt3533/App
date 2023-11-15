import AppFunction from "../../core/helpers/app/AppFunction";
import { BILLHISTORY } from "../Config/BillarApiUrl";
import {
  formatDateToLocal,
  numberWithCurrencySymbol,
} from "../Helpers/Helpers";
import { DeleteMixins, ModalMixins } from "./billar/DeleteMixins";
import { FormMixin } from "../../core/mixins/form/FormMixin";
import { paymentMethods } from "./FilterMixin";
import { urlGenerator } from "../Helpers/AxiosHelper";

export default {
  mixins: [FormMixin, DeleteMixins, ModalMixins, paymentMethods],
  data() {
    return {
      tableId: "bills-table",
      billSummation: {},
      numberWithCurrencySymbol,
      options: {
        url: BILLHISTORY,
        name: this.$t("bills_table"),
        filters: [
          {
            title: this.$t("created"),
            type: "range-picker",
            key: "date",
            option: ["today", "thisMonth", "last7Days", "nextYear"],
          },
          {
            title: this.$t("amount"),
            type: "range-filter",
            key: "amount_range",
            minTitle: this.$t("minimum_amount"),
            maxTitle: this.$t("maximum_amount"),
            maxRange: 100,
            minRange: 0,
            permission: this.$can("show_all_data") ? true : false,
          },
        ],
        columns: [
          {
            title: this.$t("invoice_number"),
            type: 'custom-html',
            key: "invoice",
            modifier: (value, row) => {
              return this.$can('show_all_data') ?
                `<a onclick="window.open(this.href,'_blank');return false;" href="${urlGenerator(`bill/${row.id}/details`)}"> ${+row.invoice.invoice_number}</a>`
                :
                `<p> ${+row.invoice.invoice_number}</p>`
                ;
            }
          },
          {
            title: this.$t("client"),
            type: "object",
            key: "invoice",
            isVisible: !!this.$can("show_all_data"),
            modifier: (invoice) => (invoice ? invoice.client_name : ""),
          },
          {
            title: this.$t("date"),
            type: "object",
            key: "created_atx",
            modifier: (createdAt) => formatDateToLocal(createdAt),
          },
          {
            title: this.$t("amount"),
            type: "object",
            key: "amount",
            modifier: (value, row) => {
              if (row) {
                const {
                  gst,
                  car_transport,
                  packing,
                  unloading,
                  unpacking,
                  loading,
                  local,
                  transport,
                  ac,
                  insuarance,
                } = row;
                if (
                  typeof gst === "number" &&
                  typeof car_transport === "number" &&
                  typeof packing === "number" &&
                  typeof unloading === "number" &&
                  typeof unpacking === "number" &&
                  typeof loading === "number" &&
                  typeof local === "number" &&
                  typeof transport === "number" &&
                  typeof ac === "number" &&
                  typeof insuarance === "number"
                ) {
                  const sum =
                    gst +
                    car_transport +
                    packing +
                    unloading +
                    unpacking +
                    loading +
                    local +
                    transport +
                    ac +
                    insuarance;
                  return numberWithCurrencySymbol(sum);
                }
              }
              return "";
            },
          },
        ],
        actions: [
          {
            title: this.$t("edit"),
            type: "edit",
            modifier: () => this.$can("update_payment_histories"),
          },
          {
            title: this.$t("view"),
            type: "view",
            url: AppFunction.getAppUrl("/bill-details"),
            modifier: () => (this.$can("show_all_data") ? true : false),
          },
          {
            title: this.$t("action_invoice_download"),
            type: "download",
            modifier: () => this.$can("invoice_download"),
          },
          {
            title: this.$t("delete"),
            type: "delete",
            modifier: () => this.$can("delete_payment_histories"),
          },
        ],
        rowLimit: 10,
        orderBy: "desc",
        responsive: true,
        showHeader: true,
        showFilter: true,
        showSearch: true,
        showAction: true,
        tableShadow: true,
        actionType: "dropdown",
        datatableWrapper: true,
        paginationType: "pagination",
      },
    };
  },
  methods: {
    getListAction(rowData, actionObj) {
      this.selectUrl = `${BILLHISTORY}/${rowData.id}`;
      if (actionObj.title === this.$t("edit")) {
        this.isModalActive = true;
      } else if (actionObj.type === "view") {
        this.selectUrl = AppFunction.getAppUrl(`/bill/${rowData.id}/details`);
        window.location = this.selectUrl;
      } else if (actionObj.type === "download") {
        window.open(AppFunction.getAppUrl(`bill-download/${rowData.id}`));
      } else if (actionObj.title === this.$t("delete")) {
        this.confirmationModalActive = true;
      }
    },
    getTableMediaAction() {
      this.$hub.$on("getTableMediaAction", (data) => {
        this.viewInvoiceDetails();
      });
    },
    PaymentRangeFilter() {
      this.axiosGet(`payment-range-filter`).then((response) => {
        let amountValueFilter = this.options.filters.find(
          (item) => item.key === "amount_range"
        );

        amountValueFilter.maxRange = response.data.max_amount;
        amountValueFilter.minRange =
          response.data.min_amount < response.data.min_amount
            ? response.data.max_amount
            : 0;
      });
    },
    getPaymentSummation() {
      this.axiosGet(`payment-summation`).then((response) => {
        this.paymentSummation = response.data;
      });
    },
  },
  mounted() {
    this.getTableMediaAction();
    this.$store.dispatch("getInvoice");
    if (this.$can("show_all_data")) {
      this.PaymentRangeFilter();
    }
  },
  created() {
    if (
      this.$can("update_payment_histories") ||
      this.$can("delete_payment_histories")
    ) {
      this.options.columns = [
        ...this.options.columns,
        {
          title: this.$t("action"),
          type: "action",
          key: "receipt",
          isVisible: true,
        },
      ];
    }
    this.getPaymentSummation();
  },
};