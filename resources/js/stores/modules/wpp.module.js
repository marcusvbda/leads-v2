import api from "~/config/libs/axios";
import io from "socket.io-client";
// const debug = require("console-development");

const state = {
    socket: {},
    status: "qr",
    qr: "",
    connection_id: null,
    token: "",
};

const getters = {
    status: (state) => state.status,
    qr: (state) => state.qr,
    token: (state) => state.token,
    socket: (state) => state.socket,
};

const mutations = {
    setSocket: (state, payload) => (state.socket = payload),
    setStatus: (state, payload) => (state.status = payload),
    setQr: (state, payload) => (state.qr = payload),
    setConnectionId: (state, payload) => (state.connection_id = payload),
    setToken: (state, payload) => (state.token = payload),
};

const serviceEvents = [
    "auth_failure",
    "error",
    "disconnected",
    "qr",
    "authenticated",
    "auth_failure",
    "ready",
    "message",
    "sent_message",
];

const actionEvents = {
    qr: (commit, resp) => {
        commit("setStatus", "qr");
        commit("setQr", resp.data);
    },
    authenticated: (commit, resp, cx) => {
        commit("setStatus", "authenticated");
        commit("setToken", cx.state.token);
    },
    ready: (commit, resp, cx) => {
        commit("setStatus", "ready");
        commit("setToken", cx.state.token);
    },
};

const actions = {
    // eslint-disable-next-line no-empty-pattern
    checkSection({}, payload) {
        return api.get(`${state.config.uri}/sessions/${payload.code}/check-status`);
    },
    initSocket(cx, payload) {
        const { commit } = cx;
        const {code} = payload;
        commit("setStatus", "qr");
        commit("setToken", code);
        const channel = `WppSocket@Session:${code}`;

        const route = `${laravel.chat.uri}:${laravel.chat.port}`;
        const socket = io(route);

        socket.on("connected", (data) => {
            socket.emit("join", channel);                

            socket.on('WppSocket.Postback', (response) => {
                if(serviceEvents.includes(response.event)){
                    actionEvents[response.event] && actionEvents[response.event](commit, response, cx);
                    if(payload[`callback_${response.event}`]){
                        payload[`callback_${response.event}`](response);
                    }
                }
            });

            commit("setConnectionId", data.id);
            commit("setSocket", socket);
        });

        return socket;
    },
    createSession(cx,payload) {
        return api.post(`/admin/sessoes-wpp/login`, payload);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
