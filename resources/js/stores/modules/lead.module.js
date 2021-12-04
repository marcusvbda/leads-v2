import api from "~/config/libs/axios";
const state = {
    tab: "active",
    departments: [],
    objections: [],
    statuses: [],
    answers: [],
    types: [],
    user: {},
    lead: {},
    leads: {
        pending: {
            current_page: 0,
            data: [],
            total: 0,
            has_more: true
        },
        active: {
            current_page: 0,
            data: [],
            total: 0,
            has_more: true
        },
        potential: {
            current_page: 0,
            data: [],
            total: 0,
            has_more: true
        }
    },
    loading: {
        active: true,
        pending: true,
        potential: true
    },
    resource_id: null,
    use_tags: false,
    filter: {
        text: "",
        status_ids: [],
        schedule: [],
    },
    preset_dates : null
};

const getters = {
    showScheduleFilter: state => {
        let results = state.statuses.filter(x => state.filter.status_ids.includes(String(x.id)) && x.value.includes("schedule"));
        return results.length > 0;
    },
    loading: state => state.loading,
    active: state => state.leads.active,
    pending: state => state.leads.pending,
    potential: state => state.leads.potential,
    user: state => state.user,
    statuses: state => state.statuses,
    lead: state => state.lead,
    objections: state => state.objections,
    types: state => state.types,
    answers: state => state.answers
};

const mutations = {
    setLead: (state, payload) => {
        state.lead = payload;
    },
    setUseTags: (state, payload) => {
        state.use_tags = payload;
    },
    setResourceId: (state, payload) => {
        state.resource_id = payload;
    },
    setTypes: (state, payload) => {
        state.types = payload;
    },
    setAnswers: (state, payload) => {
        state.answers = payload;
    },
    setObjections: (state, payload) => {
        state.objections = payload;
    },
    setDepartments: (state, payload) => {
        state.departments = payload;
    },
    setUser: (state, payload) => {
        state.user = payload;
    },
    setLeads: (state, payload) => {
        state.leads = payload;
    },
    setStatuses: (state, payload) => {
        state.statuses = payload;
    },
    setTab: (state, payload) => {
        state.tab = payload;
    },
    setLoading: (state, payload) => {
        state.loading = payload;
    },
    setFilter: (state, payload) => {
        state.filter = payload;
    },
};

const makeLeadsFilter = (cx, payload) => {
    const { state, getters } = cx;
    let filters = {
        where: [],
        where_in: [],
        raw_where: []
    };
    let filter_types = {
        potential(filters) {
            filters.where.push(["responsible_id", "=", null]);
            filters.where.push(["department_id", "=", null]);
            return filters;
        },
        pending(filters) {
            filters.where.push(["responsible_id", "=", null]);
            filters.where.push(["department_id", "=", state.user.department_id]);
            return filters;
        },
        active(filters) {
            filters.where.push(["responsible_id", "=", state.user.id]);
            return filters;
        }
    };
    if (state.filter.text) {
        // let only_numbers = state.filter.text.replace(/[^0-9]/g, '');
        let raw_where = `((lower(json_unquote(json_extract(data,'$.name'))) like '%${state.filter.text.toLowerCase()}%')`;
        raw_where += ` or (lower(json_unquote(json_extract(data,'$.email'))) like '%${state.filter.text.toLowerCase()}%'))`;
        // raw_where += ` or (lower(json_unquote(json_extract(data,'$.phones'))) like '%${only_numbers}%')`;
        // raw_where += ` or (lower(json_unquote(json_extract(data,'$.phones'))) like '%${state.filter.text.toLowerCase()}%'))`;
        filters.raw_where.push(raw_where);
    }
    if (state.filter.schedule?.length && getters.showScheduleFilter) {
        if (state.filter.schedule[0]) {
            filters.raw_where.push(
                `DATE(created_at) >= DATE('${state.filter.schedule[0]}')`
            );
        }
        if (state.filter.schedule[1]) {
            filters.raw_where.push(
                `DATE(created_at) <= TIMESTAMP('${state.filter.schedule[1]}')`
            );
        }
    }
    filters.where_in.push(["status_id", state.filter.status_ids]);
    filters = filter_types[payload.type](filters);
    return filters;
};

const actions = {
    getTypes: ({ commit }) => {
        api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\ContactType"
        }).then(({ data }) => {
            commit("setTypes", data);
        });
    },
    getAnswers: ({ commit }) => {
        api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\LeadAnswer"
        }).then(({ data }) => {
            commit("setAnswers", data);
        });
    },
    getObjections: ({ commit }) => {
        api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\Objection"
        }).then(({ data }) => {
            commit("setObjections", data);
        });
    },
    getDepartments: async ({ commit }) => {
        let { data } = await api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\Department"
        });
        commit("setDepartments", data);
        return data;
    },
    getLeads: async (cx, payload) => {
        let { state, commit } = cx;
        let filters = makeLeadsFilter(cx, payload);
        let new_page = ++state.leads[payload.type].current_page;
        if (payload.refresh) {
            new_page = 1;
        }
        let { data } = await api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\Lead",
            filters,
            per_page: 20,
            page: new_page,
            order_by: ["data->name", "desc"]
        });
        let new_state = Object.assign({}, state.leads);
        new_state[payload.type].current_page = data.current_page;
        new_state[payload.type].total = data.total;
        new_state[payload.type].last_page = data.last_page;
        if (payload.refresh) {
            new_state[payload.type].data = data.data;
        } else {
            new_state[payload.type].data = new_state[payload.type].data.concat(data.data);
        }
        new_state[payload.type].has_more = new_state[payload.type].current_page != new_state[payload.type].last_page;
        commit("setLeads", new_state);
        return data;
    },
    getStatuses: async ({ commit }) => {
        let { data } = await api.post("/vstack/json-api", {
            model: "\\App\\Http\\Models\\Status",
            order_by: ["name", "asc"],
            filters: {
                or_where_not_in: [["value", ["finished", "canceled"]]]
            }
        });
        commit("setStatuses", data);
        return data;
    },
    registerContact: async ({ state }, payload) => {
        let { data } = await api.post(`/admin/atendimento/${state.lead.code}/register-contact`, payload);
        return data;
    },
    loadLeads: async ({ state, commit, dispatch }, payload) => {
        let { refresh, type } = payload;
        if (!state.user.department_id && type == "pending") {
            return;
        }
        if (state.leads[type].has_more || refresh) {
            commit("setLoading", { ...state.loading, [type]: true });
            await dispatch("getLeads", { type, refresh });
            commit("setLoading", { ...state.loading, [type]: false });
        }
    },
    reloadAllLeads: async ({ dispatch }) => {
        await Promise.all([
            dispatch("loadLeads", { refresh: true, type: "active" }),
            dispatch("loadLeads", { refresh: true, type: "pending" }),
            dispatch("loadLeads", { refresh: true, type: "potential" })
        ]);
    },
    transferLead: async ({ state }, department_id) => {
        let { data } = await api.post(`/admin/atendimento/${state.lead.code}/transfer-department`, { department_id });
        return data;
    },
    finishLead: async ({ state }, payload) => {
        let { data } = await api.post(`/admin/atendimento/${state.lead.code}/finish`, payload);
        return data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
