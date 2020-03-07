import Vue from 'vue'

export default {
      state: {
          invoices: {}
      },
      getters: {},
      mutations: {
        FETCH_ALL: (state, [data]) => {
            Vue.set(state, "invoices", data)
        }
      },
      actions: {
          fetchAll: (context) => {
              context.commit("FETCH_ALL", [data])
          }
      }
  }