import axios from 'axios'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const axiosRetry = require('axios-retry')
axiosRetry(axios, {
	retries: 0,
	shouldResetTimeout: true,
	retryCondition: (_error) => true
})
export default axios