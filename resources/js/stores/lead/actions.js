import api from '~/config/libs/axios'

export function getTypes({ commit }) {
	api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\ContactType',
	}).then(({ data }) => {
		commit("setTypes", data)
	})
}

export function getAnswers({ commit }) {
	api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\LeadAnswer',
	}).then(({ data }) => {
		commit("setAnswers", data)
	})
}

export function getObjections({ commit }) {
	api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Objection',
	}).then(({ data }) => {
		commit("setObjections", data)
	})
}

export async function getDepartments({ commit }) {
	let { data } = await api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Department',
	})
	commit("setDepartments", data)
	return data
}

const makeLeadsFilter = (state, type) => {
	let filters = {
		where: []
	}
	if (state.user.department_id) {
		filters.where.push(["department_id", "=", state.user.department_id])
	}
	let filter_types = {
		pending(filters) {
			filters.where.push(["responsible_id", "=", null])
			return filters
		},
		active(filters) {
			filters.where.push(["responsible_id", "=", state.user.id])
			return filters
		}
	}

	filters = filter_types[type](filters)
	return filters
}

export async function getLeads({ state, commit }, type) {
	let filters = makeLeadsFilter(state, type)
	let { data } = await api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Lead',
		filters,
		per_page: 20,
		page: ++state.leads[type].current_page,
		order_by: ["data->name", "desc"]
	})
	let new_state = Object.assign({}, state.leads)
	new_state[type].current_page = data.current_page
	new_state[type].total = data.total
	new_state[type].last_page = data.last_page
	new_state[type].data = new_state[type].data.concat(data.data)
	new_state[type].has_more = new_state[type].current_page != new_state[type].last_page
	commit("setLeads", new_state)
	return data
}



