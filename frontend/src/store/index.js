import Vue from 'vue'
import Vuex from 'vuex'
import modules from './modules'

Vue.use(Vuex)
export default new Vuex.Store({
    modules,
    state: {
        dialogVisible: false,
        rightDrawerVisible: false
    },
    actions: {
        setDialogVisible (context, data){
            context.commit("SET_DIALOG_VISIBLE", data)
        },
        setRightDrawerVisible (context, data){
            context.commit("SET_RIGHT_DRAWER_VISIBLE", data)
        }
    },
    mutations: {
        SET_DIALOG_VISIBLE: (state, data) => {
            Vue.set(state, "dialogVisible", data)
        },
        SET_RIGHT_DRAWER_VISIBLE: (state, data) => {
            Vue.set(state, "rightDrawerVisible", data)
        }
    }
})
