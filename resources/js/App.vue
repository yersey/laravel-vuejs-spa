<template>
 <v-app>
   <navbar></navbar>
      <!-- <center><h2>{{ msg }}</h2></center>
      <router-link :to="{ name: 'Register'}">Register</router-link>
      <router-link :to="{ name: 'Login'}">Login</router-link>
      <router-link :to="{ name: 'Entries'}">Mirko</router-link>
      <router-link :to="{ name: 'Posts'}">Posty</router-link>
      <button v-if="isLogged" v-on:click="logout" class="btn btn-outline-success my-2 my-sm-0">Wyloguj</button><hr> -->

      <v-main class="grey lighten-3">
        <v-container class="mt-6 mb-10">
          <router-view></router-view>
          <!-- v-bind:post_="{id: 1, title: 'jakis title', body: 'body body body', imgurl: 'http://asd.asd', user_id: 1, user_name: 'Andrzej', created_at: 'created_at', digs: 1, isDig: true, comments: [] }" -->
        </v-container>
        <v-footer class="primary" absolute style="box-shadow: 0px 0 5px black;">

          <a href="https://github.com/yersey/laravel-vuejs-spa" class="white--text text-decoration-none mx-auto">
            <v-icon class="white--text">code</v-icon>
            Github
          </a>

        </v-footer>
      </v-main>
  </v-app>
</template>

<script>


import Navbar from './components/Navbar'

export default {
  name: 'app',
  components: {
    'navbar': Navbar,
  },
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  computed: {
    isLogged() {
      return this.$store.getters.isLogged;
    }
  },
  methods: {
      logout() {
          this.$store.dispatch('logout');
      }
  }
}
</script>

<style>
  
</style>
