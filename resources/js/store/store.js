import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
import router from '../router';

Vue.use(Vuex);

export const store = new Vuex.Store({
    strict: true,
    plugins: [createPersistedState()],
    state: {
        logged: localStorage.getItem('token'),
        user: {
            id: null,
            name: null,
            email: null,
            avatar: null,
            unReadNotifications: null,
            unReadMessages: null,
        }
    },
    getters: {
        isLogged: state => state.logged,
        user: state => state.user,
    },
    mutations: {
        login: (state, user) => {
            state.logged = 1;
            state.user.id = user.id;
            state.user.name = user.name;
            state.user.email = user.email;
            state.user.avatar = user.avatar;
        },
        logout: state => {
            state.logged = 0;
            state.user.id = null;
            state.user.name = null;
            state.user.email = null;
            state.user.avatar = null;
            state.user.unReadNotifications = null;
            state.user.unReadMessages = null;
        },
        setNotificationCount: (state, count) => {
            state.user.unReadNotifications = count;
        },
        setMessagesCount: (state, count) => {
            state.user.unReadMessages = count;
        },
        markAsRead: state => {
            state.user.unReadNotifications -= 1;
        },
        markAsReadMessage: state => {
            state.user.unReadMessages -= 1;
        },
    },
    actions: {
        login: (context, credential) => {
            return new Promise((resolve, reject) => {
                axios.post('/auth/login', credential)
                .then(response => {
                    localStorage.setItem('token', response.data.token.original.access_token);
                    axios.defaults.headers.common['Authorization'] = `bearer ${response.data.token.original.access_token}`;
                    context.commit('login', response.data.user);
                    router.push({name: 'Posts'})
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
            });
        },
        logout: context => {
            axios.post('/auth/logout')
            .then(() => {
                localStorage.removeItem('token');
                axios.defaults.headers.common['Authorization'] = null;
                context.commit('logout');
                router.push({name: 'Posts'}).catch(err => {});
            });
        },
        register: (context, user) => {
            return new Promise((resolve, reject) => {
                axios.post('/auth/register', user)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
            })
        },
        me: (context) => {
            axios.post('/auth/me')
            .then(response => {
                context.commit('setNotificationCount', response.data.unReadNotifications);
                context.commit('setMessagesCount', response.data.unReadMessages);
                context.commit('login', response.data.user);
            });
        },
    }
})