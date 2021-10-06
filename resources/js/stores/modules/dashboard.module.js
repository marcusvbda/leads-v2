
import api from '~/config/libs/axios'
import Message from 'element-ui/lib/message'

const state = {
    polos: [],
    date_ranges: {},
    filter: {},
    polos_qty: 0,
    departments_qty: 0,
    users_qty: 0,
    new_leads_qty: 0,
    medals: ["🥇", "🥈", "🥉"]
};

const getters = {
    getPredefinedRangeDate: state => state.predefined_filter,
    new_leads_qty : state => state.new_leads_qty,
    polos_qty : state => state.polos_qty,
    users_qty : state => state.users_qty,
    departments_qty : state => state.departments_qty,
    date_ranges : state => state.date_ranges,
    polos : state => state.polos,
    filter : state => state.filter,
    medals : state => state.medals,
};

const mutations = {
    setPoloIds : (state, payload) => {
        state.polo_ids = payload
    },
    setPolos : (state, payload) => {
        state.polos = payload
    },
    setDateRanges:(state, payload) => {
        state.date_ranges = payload
    },
    setDateRange:(state, payload)  =>{
        state.date_range = payload
    },
    setPolosQty:(state, payload)  =>{
        state.polos_qty = payload
    },
    setDepartmentsQty:(state, payload)  =>{
        state.departments_qty = payload
    },
    setUsersQty:(state, payload)  =>{
        state.users_qty = payload
    },
    setNewLeadsQty:(state, payload)  =>{
        state.new_leads_qty = payload
    },
    setFilter:(state, payload) => {
        state.filter = payload
    }   
};

const actions = {
    showError: ({ state }, error = "") => {
        Message.error({
            showClose: true,
            duration: 5000,
            dangerouslyUseHTMLString: true,
            message: error,
        })
    },
    getPolos: ({ commit }, user_id) => {
        api.post('/vstack/json-api', {
            model: '\\App\\User',
            includes: ['polos'],
            filters: {
                where: [
                    ['id', '=', user_id],
                ]
            }
        }).then(({ data }) => {
            commit("setPolos", data[0].polos)
        })
    },
    getDateRanges : ({ state, commit, dispatch }) => {
        return api.post(`${window.location.pathname}/dates/get-ranges`).then(({ data }) => {
            commit("setDateRanges", data)
            // commit('setDateRange', data[state.predefined_filter])
        }).catch(er => {
            console.log(er)
        })
    },
    getPolosQty : async ({ commit }) => {
        let { data } = await api.post('/admin/dashboard/get-data/polosQty')
        commit("setPolosQty", data)
        return data
    },
    getDepartmentsQty : async({ commit }) => {
        let { data } = await api.post('/admin/dashboard/get-data/departmentesQty')
        commit("setDepartmentsQty", data)
        return data
    },
    getUsersQty : async({ commit }) => {
        let { data } = await api.post('/admin/dashboard/get-data/usersQty')
        commit("setUsersQty", data)
        return data
    },
    getNewLeadsQty : async({ commit }, payload) => {
        let { data } = await api.post('/admin/dashboard/get-data/newLeadsQty', payload)
        commit("setNewLeadsQty", data)
        return data
    },
    getDashboardContent : async({ commit, state }, { action }) => {
        let { data } = await api.post(`/admin/dashboard/get-data/${action}`, state.filter)
        return data
    }    
};


export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
