import Vue from 'vue';
import axios from 'axios';

window.Vue = Vue;

axios.defaults.headers.common['X-CSRF-TOKEN'] = Laravel.csrfToken;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.baseApiUrl = '/api/'; 

window.axios = axios;