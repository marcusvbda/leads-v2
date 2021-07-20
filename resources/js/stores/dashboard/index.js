import state from './state'
import * as getters from './getters'
import * as mutations from './mutations'
import * as actions from './actions'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default function () {
	return new Vuex.Store({
		getters,
		mutations,
		actions,
		state
	})
}
