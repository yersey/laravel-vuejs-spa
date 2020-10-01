<template>
    <nav class="fixed-bar">
        <v-toolbar>
            <a href="#/" class="text-decoration-none d-none d-sm-inline" style="width:285px">
                <span class="blue--text text--darken-2 text-h3">vikop</span>
                <span class="orange--text d-inline">.ru</span>
            </a>

            <v-spacer></v-spacer>

            <v-tabs centered style="max-width: 500px;">
                <v-tab to="/">Wykopalisko</v-tab>
                <v-tab to="/mirko">Mirkoblog</v-tab>
            </v-tabs>

            <v-spacer></v-spacer>

            <div v-if="user.id">
                <div class="d-lg-none">
                    <v-menu offset-y>
                        <template v-slot:activator="{ on }">
                            <v-btn text v-on="on">
                            <v-icon left>expand_more</v-icon>
                            <span>
                                <v-avatar class="elevation-3" size="30"><v-img :src="'storage/'+user.avatar"></v-img></v-avatar>
                                <span class="d-none d-sm-inline">{{ user.name }}</span>
                            </span>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item router :to="`/user/${user.name}`">
                                <v-list-item-title>
                                    <v-icon left>account_circle</v-icon>
                                    <span>Profil</span>
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item  router to="/notifications">
                                <v-list-item-title>
                                    <v-badge :value="user.unReadNotifications" :content="user.unReadNotifications" overlap color="error">
                                            <v-icon>notifications</v-icon>
                                    </v-badge>
                                    <span class="ml-2">Powiadomienia</span>
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item  router to="/messages">
                                <v-list-item-title>
                                    <v-badge :value="user.unReadMessages" :content="user.unReadMessages" overlap color="error">
                                            <v-icon>mail</v-icon>
                                    </v-badge>
                                    <span class="ml-2">Wiadomości</span>
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="logout">
                                <v-list-item-title>
                                    <v-icon left>logout</v-icon>
                                    Wyloguj
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
                <div class="d-none d-lg-inline">
                    <v-badge :value="user.unReadMessages" :content="user.unReadMessages" overlap color="error">
                        <v-btn small fab to="/messages">
                            <v-icon>mail</v-icon>
                        </v-btn>
                    </v-badge>
                    <v-badge :value="user.unReadNotifications" :content="user.unReadNotifications" overlap color="error">
                        <v-btn small fab to="/notifications">
                            <v-icon>notifications</v-icon>
                        </v-btn>
                    </v-badge>
                    &nbsp;
                    <v-btn :to="`/user/${user.name}`">
                        <v-icon left>account_circle</v-icon>
                        {{ user.name }}
                    </v-btn>
                    <v-btn @click="logout">
                        <v-icon left>logout</v-icon>
                        Logout
                    </v-btn>
                </div>
            </div>
            <div v-else>
                <v-btn to="/login">
                    Login
                    <v-icon right>login</v-icon>
                </v-btn>
                <v-btn class="d-none d-lg-inline-flex" to="/register">
                    Zarejestruj się
                </v-btn>
            </div>
        </v-toolbar>
    </nav>
</template>

<script>
export default {
    name: 'Navbar',
    data() {
        return {
            drawer: false,
            links: [
                { icon: 'home', text: 'Home', route: '/'},
                { icon: 'contacts', text: 'About', route: '/about'},
            ]
        }
    },
    methods: {
        logout() {
            this.$store.dispatch('logout');
        }
    },
    computed: {
        user() {
            return this.$store.getters.user;
        },
        isLogged() {
            return this.$store.getters.isLogged;
        }
    },
    created() {
        if(this.user.id){
            this.$store.dispatch('me');
        }
    }
}
</script>

<style scoped>
    .fixed-bar {
        position: sticky;
        position: -webkit-sticky; /* for Safari */
        z-index: 2;
        top: 0.01px;
    }
</style>