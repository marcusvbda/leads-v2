export default function () {
	return {
		departments: [],
		objections: [],
		answers: [],
		types: [],
		user: {},
		lead: {},
		leads: {
			pending: {
				current_page: 0,
				data: [],
				total: 0,
				has_more: true
			},
			active: {
				current_page: 0,
				data: [],
				total: 0,
				has_more: true
			}
		},
		resource_id: null,
		use_tags: false,
	}
}
