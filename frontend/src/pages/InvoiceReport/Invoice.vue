<template>
  <div class="q-pa-md">
    <q-table
      title="Invoices"
      :data="dataTable"
      :columns="columnsTable"
      row-key="id"
      selection="single"
      :selected.sync="selected"
      :filter="filter"
      :pagination.sync="pagination"
      :loading="loading"    
      @request="onRequest"
      v-if="this.invoices.data"
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
    name: 'Invoice',
    data() {
        return {
            filter: '',
            selected: [],
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
        ...mapState("invoice", ['invoices', 'invoiceNumber']),
        columnsTable() {
            let columns = []
            if(this.invoices.data){
                let json = {}
                Object.keys(this.invoices.data[0]).forEach(function(key) {
                    json["name"] = key
                    json["label"] = key.toUpperCase()
                    json["align"] = "left"
                    json["sortable"] = false
                    json["field"] = key
                    columns.push(json)

                    json = {}
                });
            }

            return columns

        },
        dataTable() {
            return this.invoices.data
        }
    },
    methods: {
        ...mapActions("invoice", ['fetchPaginated','getRowCount']),
        onRequest (props) {

            var ref = this
            const { page, rowsPerPage, sortBy, descending } = props.pagination
            const filter = props.filter

            this.loading = true

            // emulate server
            setTimeout(() => {
                // update rowsCount with appropriate value
                ref.pagination.rowsNumber = this.invoiceNumber

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

                const returnedData = this.fetchPaginated(data)
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