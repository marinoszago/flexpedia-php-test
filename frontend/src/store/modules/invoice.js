import Vue from 'vue'
import { RequestService } from '../../statics/lib/Requests'

export default {
      state: {
          invoices: {},
          invoiceNumber: '',
          selected: []
      },
      getters: {},
      mutations: {
        FETCH_PAGINATED: (state, [data]) => {
            Vue.set(state, "invoices", data)
        },
        SET_ROW_COUNT: (state, data) => {
            Vue.set(state, "invoiceNumber", data.data)
        },
        UPDATE_SELECTED: (state, data) => {
            Vue.set(state, "selected", data.rows)
        },
        CLEAR_SELECTED: (state) => {
            Vue.set(state, "selected", [])
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
                    
					context.commit("FETCH_PAGINATED", [response.data])
					resolve(response)
				})
				.catch((err) => {
					new Error("Request failed: "+err)
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
        }
      }
  }