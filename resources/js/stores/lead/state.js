export default function () {
	return {
		tab: "active",
		departments: [],
		objections: [],
		statuses: [],
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
			},
			potential: {
				current_page: 0,
				data: [],
				total: 0,
				has_more: true
			}
		},
		loading: {
			active: true,
			pending: true,
			potential: true
		},
		resource_id: null,
		use_tags: false,
		filter: {
			text: '',
			status_ids: [],
			schedule: []
		},
	}
}
