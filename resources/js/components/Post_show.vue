<template>
    <div>
        <post class="mt-2" :post="post"></post>

        <v-row>
            <v-col class="white mt-1 mb-2 font-weight-medium elevation-1">
                Komentarze ({{ post.comments.length }}):
            </v-col>
        </v-row>

        <comment class="mb-1" v-for="comment in post.comments" :key="comment.id" :comment="comment"></comment>

        <v-row class="white elevation-1" v-if="user.id">
            <v-col cols="2" md="1" class="text-center">
                <v-avatar size="60"><v-img :src="'storage/'+user.avatar"></v-img></v-avatar>
            </v-col>
            <v-col cols="10" md="11">
                <v-textarea label="Treść komentarza" auto-grow rows="1" v-model="comment.body"></v-textarea>
                <v-btn small class="primary" @click="addComment">Zapisz</v-btn>
                <v-btn small text @click="comment.body = null">Anuluj</v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    export default {
        name: 'Post_show',
        data() {
            return {
                post: {
                        id: null, 
                        title: null, 
                        body: null, 
                        imgurl: null, 
                        user_id: null, 
                        user_name: null, 
                        created_at_: null, 
                        wykops: null, 
                        isWykop: null, 
                        comments: []
                    },
                comment: {
                    id: null,
                    body: null,
                    model: 'post'
                }
            }
        },
        methods: {
            fetchPost: function() {
                axios.get(`/post/${this.$route.params.id}`)
                .then(response => {
                    this.post = response.data.data;
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
                    this.fetchPost();
                    this.comment.body = null;
                });
            },
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            this.fetchPost();
        },
        watch: {
            $route(to, from) {
                this.fetchPost();
            }
        },
    }
</script>