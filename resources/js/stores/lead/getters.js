export function showScheduleFilter(state) {
	let results = state.statuses.filter(x => state.filter.status_ids.includes(String(x.id)) && x.value.includes("schedule"))
	return results.length > 0
}