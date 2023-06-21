import api from "~/config/libs/axios";

const state = {
    campaign : {},
    qty_leads : 0
};

const getters = {
    campaign: (state) => state.campaign,
    qty_leads: (state) => state.qty_leads,
};

const mutations = {
    setCampaign: (state, payload) => {
        state.campaign = payload;
    },
    setQtyLeads: (state, payload) => {  
        state.qty_leads = payload;
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
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
