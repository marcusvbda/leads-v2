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

const makeLeadsFilter = ({ state, getters }, payload) => {
	let filters = {
		where: [],
		where_in: [],
		raw_where: []
	}
	let filter_types = {
		potential(filters) {
			filters.where.push(["responsible_id", "=", null])
			filters.where.push(["department_id", "=", null])
			return filters
		},
		pending(filters) {
			filters.where.push(["responsible_id", "=", null])
			filters.where.push(["department_id", "=", state.user.department_id])
			return filters
		},
		active(filters) {
			filters.where.push(["responsible_id", "=", state.user.id])
			filters.where.push(["department_id", "=", state.user.department_id])
			return filters
		}
	}
	if (state.filter.text) {
		filters.raw_where.push(`lower(json_unquote(json_extract(data,'$.name'))) like '%${state.filter.text.toLowerCase()}%'`)
	}
	if (state.filter.schedule?.length && getters.showScheduleFilter) {
		if (state.filter.schedule[0]) {
			filters.raw_where.push(`TIMESTAMP(json_unquote(json_extract(data,'$.schedule'))) >= TIMESTAMP('${state.filter.schedule[0]}')`)
		}
		if (state.filter.schedule[1]) {
			filters.raw_where.push(`TIMESTAMP(json_unquote(json_extract(data,'$.schedule'))) <= TIMESTAMP('${state.filter.schedule[1]}')`)
		}
	}
	filters.where_in.push(["status_id", state.filter.status_ids])
	filters = filter_types[payload.type](filters)
	return filters
}

export async function getLeads(cx, payload) {
	let { state, commit } = cx
	let filters = makeLeadsFilter(cx, payload)
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
		order_by: ["name", "asc"],
		filters: {
			or_where_not_in: [["value", ["finished", "canceled"]]]
		}
	})
	commit("setStatuses", data)
	return data
}

export async function registerContact({ state }, payload) {
	let { data } = await api.post(`/admin/atendimento/${state.lead.code}/register-contact`, payload)
	return data
}

export async function loadLeads({ state, commit, dispatch }, payload) {
	let { refresh, type } = payload
	if (!state.user.department_id && type == "pending") {
		return
	}
	if (state.leads[type].has_more || refresh) {
		commit("setLoading", { ...state.loading, [type]: true })
		await dispatch('getLeads', { type, refresh })
		commit("setLoading", { ...state.loading, [type]: false })

	}
}

export async function reloadAllLeads({ dispatch }) {
	await Promise.all([
		dispatch('loadLeads', { refresh: true, type: 'active' }),
		dispatch('loadLeads', { refresh: true, type: 'pending' }),
		dispatch('loadLeads', { refresh: true, type: 'potential' })
	])
}

export async function transferLead({ state }, department_id) {
	let { data } = await api.post(`/admin/atendimento/${state.lead.code}/transfer-department`, { department_id })
	return data
}

export async function finishLead({ state }, payload) {
	let { data } = await api.post(`/admin/atendimento/${state.lead.code}/finish`, payload)
	return data
}