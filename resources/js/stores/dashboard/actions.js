import api from '~/config/libs/axios'
import Message from 'element-ui/lib/message'

export function showError({ state }, error = "") {
	Message.error({
		showClose: true,
		duration: 5000,
		dangerouslyUseHTMLString: true,
		message: error,
	})
}

export function getPolos({ commit, dispatch }, user_id) {
	api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\User',
		includes: ['polos'],
		filters: {
			where: [
				['id', '=', user_id],
			]
		}
	}).then(({ data }) => {
		commit("setPolos", data[0].polos)
	})
}


export function getDateRanges({ state, commit, dispatch }) {
	return api.post(`${window.location.pathname}/dates/get-ranges`).then(({ data }) => {
		commit("setDateRanges", data)
		commit('setDateRange', data[state.predefined_filter])
	}).catch(er => {
		console.log(er)
	})
}

