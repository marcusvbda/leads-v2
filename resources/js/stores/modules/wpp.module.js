import api from "~/config/libs/axios";
import io from "socket.io-client";

const state = {
    socket: {},
    status: "initializing",
    qr_code_data: {},
    connection_id: null,
};

const getters = {
    status: (state) => state.status,
    qr_code_data: (state) => state.qr_code_data,
};

const mutations = {
    setSocket: (state, payload) => (state.socket = payload),
    setStatus: (state, payload) => (state.status = payload),
    setQrCodeData: (state, payload) => (state.qr_code_data = payload),
    setConnectionId: (state, payload) => (state.connection_id = payload),
};

const actions = {
    // eslint-disable-next-line no-empty-pattern
    saveTenantToken({}, payload) {
        api.post("/admin/wpp/token-update", payload);
    },
    initSocket({ dispatch, commit }, session) {
        commit("setStatus", "initializing");
        const socket = io("http://localhost:3000", {
            reconnection: true,
            reconnectionDelay: 500,
            reconnectionAttempts: 10,
        });

        commit("setSocket", socket);

        socket.emit("start-engine", session);

        socket.on("session-updated", (data) => {
            console.log("session-updated", data);
            const actions = {
                notLogged: () => {
                    commit("setStatus", "notLogged");
                },
                qrReadSuccess: () => {
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
            dispatch("saveTenantToken", data);
        });

        socket.on("qr-generated", (data) => {
            commit("setQrCodeData", data);
        });

        socket.on("connected", (data) => {
            commit("setConnectionId", data.id);
        });

        socket.on("message-received", (data) => {
            console.log("message-received", data);
        });

        socket.on("disconnect", () => {
            dispatch("initSocket", session);
        });
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
