<template>
    <div>
        <div v-if="user.id">
            <v-row class="white rounded-t-lg" v-if="newPost">
                <v-col cols="4">
                    <a><v-img :src="post.imgurl"></v-img></a>
                </v-col>
                <v-col cols="8">
                    <div>
                        <h2><v-text-field :error-messages="errors.title" label="Tytuł" dense color="orange" v-model="post.title"></v-text-field></h2>
                        <v-text-field :error-messages="errors.imgurl" label="Link do miniaturki" dense class="mt--1" v-model="post.imgurl"></v-text-field>
                        <div>
                            <v-textarea :error-messages="errors.body" label="Opis" rows="1" color="orange" v-model="post.body"></v-textarea>
                        </div>
                        <v-btn class="primary" @click="addPost">Dodaj</v-btn>
                        <v-btn class="text" @click="newPost = false; errors = []">Anuluj</v-btn>
                    </div>
                </v-col>
            </v-row>
            <v-row v-else class="mb-3">
                <v-col class="px-0">
                    <v-btn @click="newPost = true" large class="primary elevation-5">
                        <v-icon large left>add</v-icon>
                        Dodaj nowe znalezisko
                    </v-btn>
                </v-col>
            </v-row>
        </div>

        <post class="mt-2" v-for="post_ in posts" v-bind:key="post_.id" v-bind:post="post_"></post>
        <v-pagination class="mt-3" circle prev-icon="navigate_before" next-icon="navigate_next" v-model="pagination.current_page" :length="pagination.last_page" @input="fetchPosts"></v-pagination>
    </div>
</template>

<script>
    export default {
        name: 'Posts',
        data() {
            return {
                posts: [],
                post: {
                    title: null,
                    body: null,
                    imgurl: null,
                    tags: null
                },
                pagination: {
                    current_page: 1,
                    last_page: null
                },
                newPost: false,
                errors: []
            }
        },
        methods: {
            fetchPosts: function() {
                var page_url = '/post?page='+ this.pagination.current_page;
                axios.get(page_url)
                .then(response => {
                    this.posts = response.data.data;
                    this.pagination.current_page = response.data.meta.current_page;
                    this.pagination.last_page = response.data.meta.last_page;
                });
            },
            addPost: function() {
                axios.post('/post', this.post)
                .then(response => {
                    this.fetchPosts();
                    this.clearForm();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
                
            },
            clearForm: function() {
                this.post.title = null;
                this.post.body = null;
                this.post.imgurl = null;
                this.post.tags = null;
            },
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            this.fetchPosts();
        }
    }
</script>