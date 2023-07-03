import api from "~/config/libs/axios";

const state = {
    campaign : {},
    qty_leads : 0,
    stages : [],
    fields : [],
};

const getters = {
    campaign: (state) => state.campaign,
    qty_leads: (state) => state.qty_leads,
    stages: (state) => state.stages,
    fields: (state) => state.fields,
};

const mutations = {
    setStages: (state, payload) => {
        state.stages = payload;
    },
    setCampaign: (state, payload) => {
        state.campaign = payload;
    },
    setQtyLeads: (state, payload) => {  
        state.qty_leads = payload;
    },
    setFields: (state, payload) => {
        state.fields = payload;
    }
};

const actions = {
    fetchQtyLeads: async ({ commit,state }) => {
        try {
            const {data} = await api.get(`/admin/campanhas/${state.campaign.code}/qty-leads`);
            commit("setQtyLeads", data.qty);
        } catch (error) {
            console.log(error);
        }
    },
    fetchStageLeads: async ({ commit,state },index) => {
        let stages = state.stages;
        const cursor = stages?.[index]?.leads?.next_cursor || '';
        stages[index].loading = true;
        try {
            const {data} = await api.post(`/admin/campanhas/${state.campaign.code}/fetch-leads?cursor=${cursor}`, {
                stage: index,
            });
            if(cursor) {
                data.data = stages[index].leads.data.concat(data.data);
            } 
            stages[index].leads = data;
            stages[index].loading = false;
            commit("setStages", stages);
        } catch (error) {
            console.log(error);
        }
    },
    saveLead: async ({ state },payload) => {
        api.post(`/admin/campanhas/${state.campaign.code}/save-lead`, payload);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
