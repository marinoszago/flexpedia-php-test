import Vue from 'vue'
import { RequestService } from '../../statics/lib/Requests'
import { Notify, Loading } from 'quasar'

export default {
      state: {
          invoices: {},
          invoiceNumber: '',
          selected: [],
          forPagination: {}
      },
      getters: {},
      mutations: {
        FETCH_PAGINATED: (state, [data, paramsObj]) => {
            Vue.set(state, "invoices", data)
            Vue.set(state, "forPagination", paramsObj)
        },
        SET_ROW_COUNT: (state, data) => {
            Vue.set(state, "invoiceNumber", data.data)
        },
        UPDATE_SELECTED: (state, data) => {
            Vue.set(state, "selected", data.rows)
        },
        CLEAR_SELECTED: (state) => {
            Vue.set(state, "selected", [])
        },
        RESOLVE_UPDATE: (state) => {
            
        }
      },
      actions: {
        fetchPaginated (context, paramsObj) {

            var data = {}
            var params = {}

            Object.keys(paramsObj).forEach(element => {
                params[element] = paramsObj[element]
            });

            params["dataAction"] = "getInvoices"

            data["params"] = params
            data["url"] = "invoices/request.php"
            
            return new Promise((resolve,reject) => {
				RequestService.get(data)
				.then((response) => {
                    context.commit("FETCH_PAGINATED", [response.data, paramsObj])
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
            data["url"] = "invoices/request.php"

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
        updateSelected(context, data) {
            context.commit("UPDATE_SELECTED", data)
        },
        clearSelected(context) {
            context.commit("CLEAR_SELECTED")
        },
        updateItem(context, updateData) {
            var data = {}
            var params = {}

            params["dataAction"] = "updateInvoice"

            Object.keys(updateData).forEach(key => {
                params[key] = updateData[key]
            })

            data["params"] = params
            data["url"] = "invoices/request.php"

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.patch(data)
				.then((response) => {
                    context.commit("RESOLVE_UPDATE")
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Updated successfully",
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
        createItem(context, createData) {
            var data = {}
            var params = {}

            params["dataAction"] = "insertInvoice"

            data["params"] = params
            data["url"] = "invoices/request.php"

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.post(data)
				.then((response) => {
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Created successfully",
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
        deleteItem(context, deleteData) {
            var data = {}
            var params = {}

            params["dataAction"] = "insertInvoice"

            data["params"] = params
            data["url"] = "invoices/request.php"

            Loading.show()
            return new Promise((resolve,reject) => {
				RequestService.delete(data)
				.then((response) => {
                    setTimeout(function() {
                        Loading.hide()
                        Notify.create({
                            message: "Deleted successfully",
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