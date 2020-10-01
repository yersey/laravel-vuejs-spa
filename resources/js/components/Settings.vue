<template>
    <v-row class="white elevation-2">
        <v-col>
            <p class="text-h5">Avatar</p><hr><br>
            <v-file-input 
                v-model="avatar"
                :rules="rules"
                accept="image/png, image/jpeg, image/bmp"
                placeholder="Wybierz avatar"
                prepend-icon="photo"
                label="Avatar"
                type="file"
                :error-messages="avatar_errors"
            ></v-file-input>
            <v-btn @click="sendAvatar">Wyślij</v-btn>
        </v-col>
    </v-row>
</template>

<script>
export default {
        name: 'Settings',
        data() {
            return {
                avatar_errors: [],
                rules: [
                    value => !value || value.size < 2000000 || 'Avatar musi być mniejszy niż 2 MB!',
                ],
                avatar: null,
            }
        },
        methods: {
            sendAvatar: function() {
                var formData = new FormData();
                formData.append('avatar', this.avatar);
                axios.post('/settings/avatar', formData, { headers: {'Content-Type': 'multipart/form-data'} })
                .then(response => {
                    this.avatar_errors = [];
                    this.avatar = null;
                    
                })
                .catch(error => {
                    this.avatar_errors = error.response.data.avatar;
                });
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            //
        },
    }
</script>

<style>

</style>