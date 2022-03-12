import api from "~/config/libs/axios";
import io from "socket.io-client";
const uid = () => Date.now().toString(36) + Math.random().toString(36).substr(2);
const debug = require("console-development");

const state = {
    socket: {},
    status: "qr",
    qr: "",
    connection_id: null,
    config: {
        uri: laravel.config.wpp_service.uri,
    },
    session: {},
    token: "",
};

const getters = {
    status: (state) => state.status,
    qr: (state) => state.qr,
    token: (state) => state.token,
    event_list: (state) => state.event_list,
    socket: (state) => state.socket,
};

const mutations = {
    setSocket: (state, payload) => (state.socket = payload),
    setStatus: (state, payload) => (state.status = payload),
    setQr: (state, payload) => (state.qr = payload),
    setConnectionId: (state, payload) => (state.connection_id = payload),
    setSession: (state, payload) => (state.session = payload),
    setToken: (state, payload) => (state.token = payload),
};

const actioEvents = {
    qr: (commit, data) => {
        commit("setStatus", "qr");
        commit("setQr", data);
    },
    authenticated: (commit, data, cx) => {
        commit("setStatus", "authenticated");
        commit("setToken", cx.state.session);
    },
    ready(commit, data, cx) {
        commit("setStatus", "ready");
        commit("setToken", cx.state.session);
    },
};

const actions = {
    // eslint-disable-next-line no-empty-pattern
    checkSection({}, payload) {
        return api.get(`${state.config.uri}/sessions/${payload.code}/check-status`);
    },
    initSocket(cx, payload) {
        const { commit } = cx;
        const { code } = payload;
        commit("setStatus", "qr");

        const socket = io(state.config.uri, {
            reconnection: true,
            reconnectionDelay: 500,
            reconnectionAttempts: 10,
        });

        socket.on("connected", (data) => {
            debug.log("connected", data);
            commit("setConnectionId", data.id);
            commit("setSocket", socket);
            commit("setSession", code);

            const events = payload.action_events ? payload.action_events : actioEvents;
            data.events.forEach((event) => {
                socket.on(event, (data) => {
                    debug.log(event, data);
                    events[event] && events[event](commit, data, cx);
                });
            });
        });

        socket.emit("start-engine", code);
    },
    sendDirectMessage({ state }, payload) {
        const params = { session_code: state.session_code, ...payload, uid: uid(), type: "text" };
        return api.post(`${state.config.uri}/sessions/send-direct-message`, params);
    },
    sendMessage({ state }, payload) {
        const { socket } = state;
        socket.emit("send-message", { session_code: state.session.code, ...payload, uid: uid() });
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
