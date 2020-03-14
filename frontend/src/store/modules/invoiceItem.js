import Vue from 'vue'
import { RequestService } from '../../statics/lib/Requests'
import { Notify, Loading } from 'quasar'

export default {
      state: {
          invoiceItems: {},
          invoiceItemsNumber: '',
          selectedInvoiceItems: [],
          forPaginationInvoiceItem: {}
      },
      getters: {},
      mutations: {
        FETCH_PAGINATED_INVOICE_ITEM: (state, [data, paramsObj]) => {
            Vue.set(state, "invoiceItems", data)
            Vue.set(state, "forPaginationInvoiceItem", paramsObj)
        },
        SET_ROW_COUNT: (state, data) => {
            Vue.set(state, "invoiceItemsNumber", data.data)
        },
        UPDATE_SELECTED_INVOICE_ITEM: (state, data) => {
            Vue.set(state, "selectedInvoiceItems", data.rows)
        },
        CLEAR_SELECTED_INVOICE_ITEM: (state) => {
            Vue.set(state, "selectedInvoiceItems", [])
        },
        RESOLVE_UPDATE: (state) => {
            
        }
      },
      actions: {
        fetchPaginatedInvoiceItem (context, paramsObj) {

            var data = {}
            var params = {}

            Object.keys(paramsObj).forEach(element => {
                params[element] = paramsObj[element]
            });

            params["dataAction"] = "getInvoiceItems"

            data["params"] = params
            data["url"] = "invoiceItems/request.php"
            
            return new Promise((resolve,reject) => {
				RequestService.get(data)
				.then((response) => {
                    context.commit("FETCH_PAGINATED_INVOICE_ITEM", [response.data, paramsObj])
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
					resolve(response)
				})
				.catch((err) => {
                    new Error("Request failed: "+err)
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
                    reject(err)
				})
			})
            
        },
        getRowCount (context) {

            var data = {}
            var params = {}

            params["dataAction"] = "getRowCount"

            data["params"] = params
            data["url"] = "invoiceItems/request.php"

            return new Promise((resolve,reject) => {
				RequestService.get(data)
				.then((response) => {
					context.commit("SET_ROW_COUNT", response.data)
					resolve(response)
				})
				.catch((err) => {
					new Error("Request failed: "+err)
                    reject(err)
				})
			})
            
        },
        updateSelectedInvoiceItem(context, data) {
            context.commit("UPDATE_SELECTED_INVOICE_ITEM", data)
        },
        clearSelectedInvoiceItem(context) {
            context.commit("CLEAR_SELECTED_INVOICE_ITEM")
        },
        updateInvoiceItem(context, updateData) {
            var data = {}
            var params = {}

            params["dataAction"] = "updateInvoiceItem"

            Object.keys(updateData).forEach(key => {
                params[key] = updateData[key]
            })

            data["params"] = params
            data["url"] = "invoiceItems/request.php"

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.patch(data)
				.then((response) => {
                    context.commit("RESOLVE_UPDATE")
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Updated successfully. Refresh the page to see the changes",
                            position: "top",
                            color: "positive"
                        })
                    },2000)
					resolve(response)
				})
				.catch((err) => {
                    new Error("Request failed: "+err)
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
                    reject(err)
				})
			})
        },
        createInvoiceItem(context, createData) {
            var data = {}
            var params = {}

            params["dataAction"] = "insertInvoiceItem"

            Object.keys(createData).forEach(key => {
                params[key] = createData[key]
            })


            data["params"] = params
            data["url"] = "invoiceItems/request.php"
            
            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.post(data)
				.then((response) => {
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Created successfully. Refresh the page to see the changes",
                            position: "top",
                            color: "positive"
                        })
                    },2000)
					resolve(response)
				})
				.catch((err) => {
                    new Error("Request failed: "+err)
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
                    reject(err)
				})
			})
        },
        deleteInvoiceItem(context, deleteData) {
            var data = {}
            var params = {}

            Object.keys(deleteData).forEach(key => {
                params[key] = deleteData[key]
            })

            params["dataAction"] = "deleteInvoiceItem"

            data["params"] = params
            data["url"] = "invoiceItems/request.php"

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.delete(data)
				.then((response) => {
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Deleted successfully. Refresh the page to see the changes",
                            position: "top",
                            color: "positive"
                        })
                    },2000)
					resolve(response)
				})
				.catch((err) => {
                    new Error("Request failed: "+err)
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
                    reject(err)
				})
			})
        },
        exportDataToCsvItems(context) {
            var data = {}
            var params = {}

            params["dataAction"] = "exportToCsv"

            data["params"] = params
            data["url"] = "invoiceItems/request.php"
            data["responseType"] = "blob"
            data["filename_prefix"] = "invoice_items_transaction_"
            

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.getBlob(data)
				.then((response) => {
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Exported successfully",
                            position: "top",
                            color: "positive"
                        })
                    },2000)
					resolve(response)
				})
				.catch((err) => {
                    new Error("Request failed: "+err)
                    setTimeout(function() {
                        Loading.hide()
                    },2000)
                    reject(err)
				})
			})
        }
      }
  }