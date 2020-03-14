import Vue from 'vue'
import Vuex from 'vuex'
import modules from './modules'

Vue.use(Vuex)
export default new Vuex.Store({
    modules,
    state: {
        dialogVisible: false
    },
    actions: {
        setDialogVisible (context, data){
            context.commit("SET_DIALOG_VISIBLE", data)
        }
    },
    mutations: {
        SET_DIALOG_VISIBLE: (state, data) => {
            Vue.set(state, "dialogVisible", data)
        }
    }
})
