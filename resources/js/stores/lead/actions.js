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