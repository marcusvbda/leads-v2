import api from "~/config/libs/axios";
import io from "socket.io-client";
const uid = () => Date.now().toString(36) + Math.random().toString(36).substr(2);

const state = {
    socket: {},
    status: "initializing",
    qr_code_data: {},
    connection_id: null,
    // keep_alive_interval: null,
    config: {
        uri: laravel.config.wpp_service.uri,
    },
    session: {},
    token: {},
};

const getters = {
    status: (state) => state.status,
    qr_code_data: (state) => state.qr_code_data,
    token: (state) => state.token,
};

const mutations = {
    setSocket: (state, payload) => (state.socket = payload),
    setStatus: (state, payload) => (state.status = payload),
    setQrCodeData: (state, payload) => (state.qr_code_data = payload),
    setConnectionId: (state, payload) => (state.connection_id = payload),
    // setKeepAliveInterval: (state, payload) => (state.keep_alive_interval = payload),
    setSession: (state, payload) => (state.session = payload),
    setToken: (state, payload) => (state.token = payload),
};

const actions = {
    // stopKeepAlive({ state }) {
    //     clearInterval(state.keep_alive_interval);
    // },
    // startKeepAlive({ commit, dispatch, state }) {
    //     dispatch("stopKeepAlive");
    //     const keepAliveHandler = setInterval(() => {
    //         api.get(`${state.config.uri}/sessions/get-status/${state.session.code}`, { timeout: 20000 })
    //             .then(({ data }) => {
    //                 if (data !== "connected") {
    //                     dispatch("stopKeepAlive");
    //                     dispatch("initSocket", state.session);
    //                 }
    //             })
    //             .catch(() => {
    //                 dispatch("stopKeepAlive");
    //                 dispatch("initSocket", state.session);
    //             });
    //     }, 3000);
    //     commit("setKeepAliveInterval", keepAliveHandler);
    // },
    // eslint-disable-next-line no-empty-pattern
    // saveTenantToken({}, payload) {
    //     return api.post("/admin/wpp/token-update", payload);
    // },
    // eslint-disable-next-line no-empty-pattern
    checkSection({}, payload) {
        return api.get(`${state.config.uri}/sessions/get-status/${payload}`);
    },
    // eslint-disable-next-line no-empty-pattern
    logSection({}, payload) {
        api.post(`${state.config.uri}/sessions/login`, payload);
    },
    initSocket({ dispatch, commit }, session) {
        commit("setStatus", "initializing");
        const socket = io(state.config.uri, {
            reconnection: true,
            reconnectionDelay: 500,
            reconnectionAttempts: 10,
        });

        commit("setSocket", socket);
        commit("setSession", session);

        socket.emit("start-engine", session);

        socket.on("session-updated", (data) => {
            console.log("session-updated", data);
            const actions = {
                notLogged: () => {
                    commit("setStatus", "notLogged");
                },
                qrReadSuccess: () => {
                    // dispatch("startKeepAlive");
                    commit("setStatus", "logged");
                },
                isLogged: () => {
                    commit("setStatus", "logged");
                },
            };

            if (actions[data.statusSession]) {
                actions[data.statusSession]();
            }
        });

        socket.on("token-generated", (data) => {
            commit("setToken", JSON.stringify(data));
            // dispatch("saveTenantToken", data).then(() => {
            //     dispatch("startKeepAlive");
            // });
        });

        socket.on("qr-generated", (data) => {
            commit("setStatus", "notLogged");
            commit("setQrCodeData", data);
        });

        socket.on("connected", (data) => {
            commit("setConnectionId", data.id);
        });

        // socket.on("message-received", (data) => {
        //     console.log("message-received", data);
        // });

        socket.on("disconnect", () => {
            dispatch("initSocket", session);
        });

        socket.on("message-sent", (res) => {
            console.log("message-sent", res);
        });

        socket.on("message-failed", (res) => {
            console.log("message-failed", res);
        });
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
