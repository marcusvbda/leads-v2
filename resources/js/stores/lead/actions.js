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

const makeLeadsFilter = (state, payload) => {
	let filters = {
		where: [],
		where_in: [],
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
	filters.where.push(["data->name", "like", `%${(payload.filter.text ?? '').toLowerCase()}%`])
	filters.where_in.push(["status_id", payload.filter.status_ids])
	filters = filter_types[payload.type](filters)
	return filters
}

export async function getLeads({ state, commit }, payload) {
	let filters = makeLeadsFilter(state, payload)
	let new_page = ++state.leads[payload.type].current_page
	if (payload.refresh) {
		new_page = 1
	}
	let { data } = await api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Lead',
		filters,
		per_page: 20,
		page: new_page,
		order_by: ["data->name", "desc"]
	})
	let new_state = Object.assign({}, state.leads)
	new_state[payload.type].current_page = data.current_page
	new_state[payload.type].total = data.total
	new_state[payload.type].last_page = data.last_page
	if (payload.refresh) {
		new_state[payload.type].data = data.data
	} else {
		new_state[payload.type].data = new_state[payload.type].data.concat(data.data)
	}
	new_state[payload.type].has_more = new_state[payload.type].current_page != new_state[payload.type].last_page
	commit("setLeads", new_state)
	return data
}

export async function getStatuses({ commit }) {
	let { data } = await api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Status',
		order_by: ["seq", "desc"]
	})
	commit("setStatuses", data)
	return data
}

export async function registerContact({ state }, payload) {
	let { data } = await api.post(`/admin/atendimento/${state.lead.code}/register-contact`, payload)
	return data
}