<template>
  <div class="q-pa-md">
    <q-table
      title="Invoice Items"
      :data="dataTable"
      :columns="columnsTable"
      row-key="id"
      selection="single"
      :selected.sync="selectedItems"
      :filter="filter"
      :pagination.sync="pagination"
      :loading="loading"    
      @request="onRequest"
      v-if="this.invoiceItems.data"
      @selection="handleSelection"
    >
        <template v-slot:top-right>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
            <template v-slot:append>
                <q-icon name="search" />
            </template>
            </q-input>
        </template>
    </q-table>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    name: 'InvoiceItems',
    data() {
        return {
            filter: '',
            selectedItems: [],
            loading: false,
            pagination: {
                sortBy: 'id',
                descending: false,
                page: 1,
                rowsPerPage: 5,
                rowsNumber: 10
            },
        } 
    },
    computed: {
        ...mapState("invoiceItem", ['invoiceItems', 'invoiceItemsNumber']),
        columnsTable() {
            let columns = []
            if(this.invoiceItems.data){
                let json = {}
                let n = 0;
                Object.keys(this.invoiceItems.data[0]).forEach(function(key) {
                    json["name"] = key
                    json["label"] = key.toLowerCase()
                    json["align"] = "left"
                    json["sortable"] = false
                    json["field"] = key
                    if(n % 2 == 0){
                        json["classes"] = 'bg-grey-2 ellipsis'
                        json["headerClasses"] = 'bg-teal text-white'
                    }
                    columns.push(json)

                    n++;
                    json = {}
                });
            }

            return columns

        },
        dataTable() {
            return this.invoiceItems.data
        }
    },
    methods: {
        ...mapActions("invoiceItem", ['fetchPaginatedInvoiceItem','getRowCount', 'updateSelectedInvoiceItem','clearSelectedInvoiceItem']),
        ...mapActions(["setDialogVisible"]),
        onRequest (props) {

            var ref = this
            const { page, rowsPerPage, sortBy, descending } = props.pagination
            const filter = props.filter

            this.loading = true
            this.$q.loading.show()

            // emulate server
            setTimeout(() => {
                // update rowsCount with appropriate value
                ref.pagination.rowsNumber = this.invoiceItemsNumber

                // get all rows if "All" (0) is selected
                const fetchCount = rowsPerPage === 0 ? this.pagination.rowsNumber : rowsPerPage

                // calculate starting row of data
                const startRow = (page - 1) * rowsPerPage

                // fetch data from "server"

                var data = {}

                data["startRow"] = startRow
                data["fetchCount"] = fetchCount
                data["filter"] = filter
                data["sortBy"] = sortBy
                data["descending"] = descending

                const returnedData = this.fetchPaginatedInvoiceItem(data)
                .then((response) => {

                    // don't forget to update local pagination object
                    this.pagination.page = page
                    this.pagination.rowsPerPage = rowsPerPage
                    this.pagination.sortBy = sortBy
                    this.pagination.descending = descending

                    // ...and turn of loading indicator
                    this.loading = false
                })

                
            }, 1500)
        },
        handleSelection(select) {
            this.setDialogVisible(false)
            
            if(select.added)
                this.updateSelectedInvoiceItem(select)
            else{
                this.clearSelectedInvoiceItem()
                let data = 
                {
                    "rows": {
                        "invoice_id": "",
                        "name": "",
                        "amount": "",
                        "created_at" : ""
                    }
                }
                this.updateSelectedInvoiceItem(data)
                
            }
                
        }

    },
    mounted(){
        this.getRowCount()
    },
    created() {
        this.onRequest({
            pagination: this.pagination,
            filter: undefined
        })
    }
    
}
</script>

<style>

</style>