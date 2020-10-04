require('./bootstrap');

import Vue from 'vue'
import App from './App.vue'
import router from './router/index'
import { store } from './store/store'
import vuetify from './plugins/vuetify'

import Post from './components/Post'
import Comment from './components/Comment'
import Entry from './components/Entry'
import Conversations from './components/Conversations'
import Conversation from './components/Conversation'

Vue.component('post', Post);
Vue.component('comment', Comment);
Vue.component('entry', Entry);
Vue.component('conversations', Conversations);
Vue.component('conversation', Conversation);

axios.interceptors.response.use( (response) => {
  // Return a successful response back to the calling service
  return response;
}, (error) => {
  // Return any error which is not due to authentication back to the calling service
  if (error.response.status !== 401) {
    return new Promise((resolve, reject) => {
      reject(error);
    });
  }

  // Logout user if token refresh didn't work or user is disabled
  if (error.config.url == '/auth/refresh' || error.response.message == 'Account is disabled.') {
    store.dispatch('logout');
    router.push({ name: 'Posts' }).catch(err => {});

    return new Promise((resolve, reject) => {
      reject(error);
    });
  }

  if(!store.getters.user.id){
    router.push({name: 'Login'});
    return null;
  } 

  // Try request again with new token
  return axios.post('/auth/refresh')
    .then((response) => {
      var token = response.data.access_token;
      
      // New request with new token
      const config = error.config;
      localStorage.setItem('token', token);
      axios.defaults.headers.common['Authorization'] = `bearer ${token}`;
      config.headers['Authorization'] = `Bearer ${token}`;

      return new Promise((resolve, reject) => {
        axios.request(config).then(response => {
          resolve(response);
        }).catch((error) => {
          reject(error);
        })
      });
      
    })
    .catch((error) => {
      store.dispatch('logout');
      Promise.reject(error);
    });
});

new Vue({
  el: '#app',
  vuetify,
  store,
  router, 
  axios,
  components: { App },
  template: '<App/>'
})
