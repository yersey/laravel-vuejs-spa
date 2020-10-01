<template>
    <div>
        <v-list>
            <v-list-item-group color="primary">
                <v-list-item v-for="conversation in conversations" :key="conversation.conversation" :href="'#/messages/'+conversation.conversation" @click="conversation.read_at = true">
                    <v-col cols="7" md="4" class="text-center" >
                        <v-avatar class="elevation-2" size="60"><v-img :src="'storage/'+conversation.user_avatar"></v-img></v-avatar>
                    </v-col>
                    <v-col cols="5" md="8" class="text-truncate d-none d-md-block" :class="conversation.read_at || conversation.user_id == user.id ? null : 'font-weight-bold'">
                        <a class="text-h6 ">{{ conversation.with_user_name }}</a><br>
                        {{ conversation.message }}
                    </v-col>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </div>
</template>

<script>
    export default {
        name: 'Conversations',
        props: ['conversations'],
        data() {
            return {
                //conversations: []
            }
        },
        methods: {
            fetchConversations: function() {
                axios.get('/conversations')
                .then(response => {
                    this.conversations = response.data.data;
                });
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            //this.fetchConversations();
        }
    }
</script>