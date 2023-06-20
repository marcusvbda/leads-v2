import api from "~/config/libs/axios";

const state = {
    polos: [],
    polos_qty: 0,
    departments_qty: 0,
    users_qty: 0,
    new_leads_qty: 0,
};

const getters = {
    polos_qty: (state) => state.polos_qty,
    users_qty: (state) => state.users_qty,
    departments_qty: (state) => state.departments_qty,
    polos: (state) => state.polos,
    new_leads_qty: (state) => state.new_leads_qty,
};

const mutations = {
    setPolosQty: (state, payload) => {
        state.polos_qty = payload;
    },
    setDepartmentsQty: (state, payload) => {
        state.departments_qty = payload;
    },
    setUsersQty: (state, payload) => {
        state.users_qty = payload;
    },
    setFilter: (state, payload) => {
        state.filter = payload;
    },
    setNewLeadsQty: (state, payload) => {
        state.new_leads_qty = payload;
    }
};

const actions = {
    getPolos: ({ commit }, user_id) => {
        api.post("/vstack/json-api", {
            model: "\\App\\User",
            includes: ["polos"],
            filters: {
                where: [["id", "=", user_id]],
            },
        }).then(({ data }) => {
            commit("setPolos", data[0].polos);
        });
    },
    getPolosQty: async ({ commit }) => {
        let { data } = await api.post("/admin/dashboard/get-data/polosQty");
        commit("setPolosQty", data);
        return data;
    },
    getDepartmentsQty: async ({ commit }) => {
        let { data } = await api.post("/admin/dashboard/get-data/departmentesQty");
        commit("setDepartmentsQty", data);
        return data;
    },
    getUsersQty: async ({ commit }) => {
        let { data } = await api.post("/admin/dashboard/get-data/usersQty");
        commit("setUsersQty", data);
        return data;
    },
    getNewLeadsQty: async ({ commit }, payload) => {
        let { data } = await api.post("/admin/dashboard/get-data/newLeadsQty", payload);
        commit("setNewLeadsQty", data);
        return data;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
