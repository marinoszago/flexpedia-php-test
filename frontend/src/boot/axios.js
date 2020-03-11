import Vue from 'vue'
import axios from 'axios'

Vue.prototype.$axios = axios


axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.baseURL = 'http://localhost/flexpedia-php-test/backend/src/api/';