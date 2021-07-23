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

export function getPolos({ commit }, user_id) {
	api.post('/vstack/json-api', {
		model: '\\App\\User',
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
		// commit('setDateRange', data[state.predefined_filter])
	}).catch(er => {
		console.log(er)
	})
}

export async function getPolosQty({ commit }) {
	let { data } = await api.post('/admin/dashboard/get-data/polosQty')
	commit("setPolosQty", data)
	return data
}

export async function getDepartmentsQty({ commit }) {
	let { data } = await api.post('/admin/dashboard/get-data/departmentesQty')
	commit("setDepartmentsQty", data)
	return data
}

export async function getUsersQty({ commit }) {
	let { data } = await api.post('/admin/dashboard/get-data/usersQty')
	commit("setUsersQty", data)
	return data
}

export async function getNewLeadsQty({ commit }, payload) {
	let { data } = await api.post('/admin/dashboard/get-data/newLeadsQty', payload)
	commit("setNewLeadsQty", data)
	return data
}

export async function getDashboardContent({ commit, state }, { action }) {
	let { data } = await api.post(`/admin/dashboard/get-data/${action}`, state.filter)
	return data
}
