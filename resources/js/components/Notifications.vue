<template>
  <v-row class="white elevation-2">
    <v-col>
    <div v-for="notification in orderedNotifications" :key="notification.id">
      <v-row>
        <v-col cols="2" md="1" class="text-center">
            <v-avatar size="60"><v-img :src="'storage/'+notification.data.user_avatar"></v-img></v-avatar>
        </v-col>
        <v-col cols="10" md="11" align-self="center">
          <div v-if="notification.type == 'App\\Notifications\\Mentioned'">
            <a :href="`/#/user/${notification.data.user_name}`" class="text-decoration-none">{{ notification.data.user_name }}</a> wspomniał o tobie w 
            <a v-if="notification.data.from_model == 'entry'" :href="`/#/mirko/${notification.data.from_model_id}`" class="text-decoration-none">tym wpisie</a> 
            <a v-if="notification.data.from_model == 'comment' && notification.data.comment_parent == 'entry'" :href="`/#/mirko/${notification.data.from_model_id}`" class="text-decoration-none">tym komentarzu</a> 
            <a v-if="notification.data.from_model == 'comment' && notification.data.comment_parent == 'post'" :href="`/#/post/${notification.data.from_model_id}`" class="text-decoration-none">tym komentarzu</a> 
            <v-icon v-if="!notification.read_at" @click="markAsRead(notification.id)" class="error--text float-right">close</v-icon>
          </div>
          <div v-else>
            <a :href="`/#/user/${notification.data.user_name}`" class="text-decoration-none">{{ notification.data.user_name }}</a> użył tagu, który obserwujesz w
            <a v-if="notification.data.from_model == 'entry'" :href="`/#/mirko/${notification.data.from_model_id}`" class="text-decoration-none">tym wpisie</a> 
            <a v-if="notification.data.from_model == 'post'" :href="`/#/post/${notification.data.from_model_id}`" class="text-decoration-none">tym znalezisku</a> 
            <v-icon v-if="!notification.read_at" @click="markAsRead(notification.id)" class="error--text float-right">close</v-icon>
          </div>
        </v-col>
      </v-row>
    </div>
    <h3 v-if="!notifications.length" class="text-center">Brak powiadomień</h3>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'Notifications',
  data () {
    return {
      notifications: []
    }
  },
  methods: {
    fetchNotifications: function() {
      axios.get('/notifications')
      .then(response => {
        this.notifications = response.data;
      });
    },
    markAsRead: function(id) {
      axios.get(`/notification/${id}/markasread`)
      .then(response => {
        this.fetchNotifications();
        this.$store.commit('markAsRead');
      });
    }
  },
  created() {
      this.fetchNotifications();
  },
  computed: {
    orderedNotifications: function () {
      return _.orderBy(this.notifications, 'read_at', 'desc')
    }
  }
}
</script>

<style>

</style>