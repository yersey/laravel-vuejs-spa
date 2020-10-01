import Vue from 'vue'
import Router from 'vue-router'
import Main from '../components/Main'
import Two from '../components/Two'
import Login from '../components/Login'
import Register from '../components/Register'
import Wpisy from '../components/Wpisy'
import Wpis_show from '../components/Wpis_show'
import Posts from '../components/Posts'
import Post_show from '../components/Post_show'
import Not_found from '../components/Not_found'
import Notifications from '../components/Notifications'
import Messages from '../components/Messages'
import Profile from '../components/Profile'
import Tag from '../components/Tag'
import Tags from '../components/Tags'
import Settings from '../components/Settings'

Vue.use(Router)

export default new Router({
    //mode: 'history',
    routes: [
    {
        path: '*',
        name: 'Not_found',
        component: Not_found
    },
    {
        path: '/main',
        name: 'Main',
        component: Main
    },
    {
        path: '/',
        name: 'Posts',
        component: Posts
    },
    {
        path: '/two',
        name: 'Two',
        component: Two
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/mirko',
        name: 'Wpisy',
        component: Wpisy
    },
    {
        path: '/mirko/:id',
        name: 'Wpis_show',
        component: Wpis_show
    },
    {
        path: '/post/:id',
        name: 'Post_show',
        component: Post_show
    },
    {
        path: '/notifications',
        name: 'Notifications',
        component: Notifications
    },
    {
        path: '/messages/:conversation?',
        name: 'Messages',
        component: Messages
    },
    {
        path: '/user/:id',
        name: 'Profile',
        component: Profile
    },
    {
        path: '/tags',
        name: 'Tags',
        component: Tags
    },
    {
        path: '/tag/:name',
        name: 'Tag',
        component: Tag
    },
    {
        path: '/settings',
        name: 'Settings',
        component: Settings
    }
    ]
})

