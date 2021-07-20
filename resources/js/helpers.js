import Vue from 'vue'

Vue.prototype.$getEnabledIcons = function (enabled) {
	const icons = { true: '🟢', false: '🔴' }
	return icons[enabled ? 'true' : 'false']
}