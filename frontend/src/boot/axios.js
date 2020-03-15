import Vue from 'vue'
import axios from 'axios'

Vue.prototype.$axios = axios


axios.defaults.headers.post['Content-Type'] = 'application/json';

/**
 * This is the base url that I setup inorder to handle the requests of the api
 */
axios.defaults.baseURL = 'http://localhost/flexpedia-php-test/backend/src/api/';

///Interceptors

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent

    console.log("Request interceptor",config)
    return config;
}, function (error) {
    // Do something with request error

    console.log("Request interceptor error",error)
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Do something with response data

    console.log("Response interceptor",response)
    return response;
}, function (error) {
    // Do something with response error
    
    console.log("Response interceptor error",error)
    return Promise.reject(error);
});
