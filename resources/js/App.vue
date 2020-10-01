<template>
 <v-app>
   <navbar></navbar>
      <!-- <center><h2>{{ msg }}</h2></center>
      <router-link :to="{ name: 'Register'}">Register</router-link>
      <router-link :to="{ name: 'Login'}">Login</router-link>
      <router-link :to="{ name: 'Wpisy'}">Mirko</router-link>
      <router-link :to="{ name: 'Posts'}">Posty</router-link>
      <button v-if="isLogged" v-on:click="logout" class="btn btn-outline-success my-2 my-sm-0">Wyloguj</button><hr> -->

      <v-main class="grey lighten-3">
        <v-container class="mt-6">
          <router-view></router-view>
          <!-- v-bind:post_="{id: 1, title: 'jakis title', body: 'body body body', imgurl: 'http://asd.asd', user_id: 1, user_name: 'Andrzej', created_at: 'created_at', wykops: 1, isWykop: true, comments: [] }" -->
        </v-container>
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
