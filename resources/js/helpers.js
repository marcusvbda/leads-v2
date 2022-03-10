import Vue from "vue";

Vue.prototype.$getEnabledIcons = function (enabled) {
    const icons = { true: "🟢", false: "🔴" };
    return icons[enabled ? "true" : "false"];
};

Vue.prototype.$uid = () => Date.now().toString(36) + Math.random().toString(36).substr(2);
