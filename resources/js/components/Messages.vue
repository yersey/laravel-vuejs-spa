<template>
    <v-row class="white rounded-lg">
        <v-col cols="3" md="4">
            <a @click="new_message=!new_message" v-if="user.id"><v-icon class="float-right">add_comment</v-icon></a>
            <conversations :conversations="conversations"></conversations>
        </v-col>
        <v-col cols="9" md="8">
            <div v-if="new_message || conversations.length == 0">
                <v-text-field v-model="message.message" label="Treść wiadomości..."></v-text-field>
                <v-text-field v-model="message.to_id" label="Nazwa użytkownika"></v-text-field>
                <button @click="addMessage">Wyślij wiadomość</button>
            </div>
            <div v-else>
                <conversation :conversation="conversation" :user_name="user_name"></conversation>
                <v-row>
                    <v-col cols="8" md="10">
                        <v-text-field v-model="message.message" label="Treść wiadomości..."></v-text-field>
                        <v-text-field v-model="message.with_user_name" v-show="false"></v-text-field>
                    </v-col>
                    <v-col cols="4" md="2">
                        <v-btn @click="addMessage" class="mt-5">Wyślij</v-btn>
                    </v-col>
                </v-row>
            </div>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: 'Messages',
        data() {
            return {
                conversations: [],
                conversation: [],
                user_name: null,
                new_message: false,
                message: {
                    message: null,
                    to_id: null,
                },
            }
        },
        methods: {
            fetchConversations: function() {
                axios.get('/conversations')
                .then(response => {
                    this.conversations = response.data.data;
                });
            },
            fetchConversation: function() {
                axios.get(`/conversation/${this.$route.params.conversation ? this.$route.params.conversation : ''}`)
                .then(response => {
                    this.conversation = response.data.messages;
                    this.user_name = response.data.user.with_user_name;
                    this.message.to_id = response.data.user.with_user_id;
                });
            },
            addMessage: function() {
                axios.post('/message', this.message)
                .then(response => {
                    this.clearForm();
                    this.fetchConversations();
                    this.fetchConversation();
                });
                
            },
            clearForm: function() {
                this.message.message = null;
                this.message.to_id = null;
                this.new_message = false;
            },
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            this.fetchConversations();
            this.fetchConversation();
        },
        watch: {
            $route(to, from) {
                this.fetchConversation();
            }
        },
    }
</script>