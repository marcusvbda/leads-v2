import axios from "axios";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import axiosRetry from "axios-retry";
axiosRetry(axios, {
  retries: 0,
  shouldResetTimeout: true,
  retryCondition: () => true,
});
export default axios;
