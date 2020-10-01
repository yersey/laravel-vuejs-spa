<template>
    <div>
        <wpis @odpowiedz="odpowiedz($event)" @daniel="wypisz($event)" class="mt-2" :wpis="wpis"></wpis>

        <v-row class="white">
            <v-col cols="1"></v-col>
            <v-col>
                <comment v-for="comment in wpis.comments" :key="comment.id" :comment="comment"></comment>
                <v-row class="white" v-if="user.id">
                    <v-col cols="2" md="1" class="text-center">
                        <v-avatar size="60"><v-img :src="'storage/'+user.avatar"></v-img></v-avatar>
                    </v-col>
                    <v-col cols="10" md="11">
                        <v-textarea label="Treść komentarza" auto-grow rows="1" v-model="comment.body"></v-textarea>
                        <v-btn small class="primary" @click="addComment">Zapisz</v-btn>
                        <v-btn small text @click="comment.body = null">Anuluj</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    export default {
        name: 'Post_show',
        data() {
            return {
                wpis: {
                        id: null, 
                        body: null, 
                        user_id: null, 
                        user_name: null, 
                        created_at_: null, 
                        pluses: null, 
                        isPlus: null, 
                        comments: []
                    },
                comment: {
                    id: null,
                    body: '',
                    model: 'wpis'
                }
            }
        },
        methods: {
            fetchWpis: function() {
                axios.get(`/mirko/${this.$route.params.id}`)
                .then(response => {
                    this.wpis = response.data.data;
                    this.comment.id = response.data.data.id;
                })
                .catch(error => {
                    if(error.response.status == 404){
                        this.$router.push({name: 'Not_found', params: {0: '404'}});
                    }
                });
            },
            addComment: function() {
                axios.post('/comment', this.comment)
                .then(response => {
                    this.fetchWpis();
                    this.comment.body = null;
                });
            },
            odpowiedz: function(wpis) {
                this.comment.body += '@'+wpis.user_name+': ';
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            this.fetchWpis();
        },
        watch: {
            $route(to, from) {
                this.fetchWpis();
            }
        },
    }
</script>