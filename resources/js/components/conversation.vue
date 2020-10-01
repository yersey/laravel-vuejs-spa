<template>
    <v-row>
        <v-col cols="12" class="order-first">
            <a :href="'/#/user/'+user_name" class="text-decoration-none text-h6">{{ user_name }}</a>
            <hr>
        </v-col>
        <v-col cols="12" class="py-1" v-for="message in conversation" :key="message.id">
            <v-chip :class="message.user_id == user.id ? 'float-right primary text-subtitle-1' : 'float-left text-subtitle-1'" class="text-wrap" style=" word-break: break-all">
                {{ message.message }}
            </v-chip>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: 'Conversation',
        props: ['conversation', 'user_name'],
        data() {
            return {
                //conversation: [],
                //user_name: null
            }
        },
        methods: {
            fetchConversation: function() {
                axios.get(`/conversation/${this.$route.params.conversation ? this.$route.params.conversation : ''}`)
                .then(response => {
                    this.conversation = response.data.messages;
                    this.user_name = response.data.user.with_user_name;
                });
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
    }
</script>

<style>
    
</style>