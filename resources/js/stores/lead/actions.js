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
	if (type == 'pending') {
		filters.where.push(["responsible_id", "=", null])
	}
	return filters
}

export async function getPendingLeads({ state, commit }) {
	let filters = makeLeadsFilter(state, "pending")
	let { data } = await api.post('/vstack/json-api', {
		model: '\\App\\Http\\Models\\Lead',
		filters
	})
	commit("setDepartments", data)
	return data
}



