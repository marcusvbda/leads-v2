// import api from "~/config/libs/axios";

const state = {
    inputsOptions : []
};

const getters = {
    inputsOptions: (state) => state.inputsOptions,
};

const mutations = {
    setInputsOptions: (state, payload) => {
        state.inputsOptions = payload;
    }
};

const actions = {
    // 
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
