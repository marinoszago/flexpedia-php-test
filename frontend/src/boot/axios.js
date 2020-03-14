import Vue from 'vue'
import axios from 'axios'

Vue.prototype.$axios = axios


axios.defaults.headers.post['Content-Type'] = 'application/json';

/**
 * This is the base url that I setup inorder to handle the requests of the api
 */
axios.defaults.baseURL = 'http://localhost/flexpedia-php-test/backend/src/api/';