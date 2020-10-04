<template>
    <div v-if="!deleted">
    <v-row class="white elevation-1">
        <v-col cols="2" lg="1" align-self="center" class="text-center"> 
            <a v-if="post.user_id != user.id">
                <v-btn v-if="post.isDig" small text @click="unDig(post.id)">cofnij</v-btn>
                <v-btn v-else small class="primary" @click="dig(post.id)">wykop</v-btn>
            </a>
            <div style="margin-top: 5px; border: 3px solid #1976d2; border-radius: 10px; color: orangered; text-align: center; font-weight: bold; width:65px;" class="mx-auto">
                {{ post.digs }}
            </div>
        </v-col>
        <v-col cols="10" md="4" lg="3" class="pl-7 pl-sm-5 pl-sm-0">
            <a :href="`#/post/${post.id}`"><v-img class="width: 100%" aspect-ratio="1.4" :src="post.imgurl"></v-img></a>
        </v-col>
        <v-col cols="12" md="6" lg="8" class="px-5" @mouseover="show" @mouseout="hide">
            <div v-if="edit">
                <h2><v-text-field :error-messages="errors.title" dense color="orange" v-model="post.title"></v-text-field></h2>
                <v-text-field :error-messages="errors.imgurl" dense class="mt--1" v-model="post.imgurl"></v-text-field>
                <div>
                    <v-textarea :error-messages="errors.body" rows="1" color="orange" v-model="post.body"></v-textarea>
                </div>
                <v-btn small class="primary" @click="updatePost(post)">zapisz</v-btn>
                <v-btn small text @click="edit = false">anuluj</v-btn>
            </div>
            <div v-else>
                <div v-if="post.user_id == user.id" class="float-right">
                    <transition name="fade">
                    <div v-show="state">
                        <v-btn @click="editPost" x-small fab><v-icon>edit</v-icon></v-btn>
                        <v-btn @click="delete_confirmation = true" x-small fab><v-icon>delete</v-icon></v-btn>
                    </div>
                    </transition>
                    <v-dialog v-model="delete_confirmation" persistent max-width="290">
                        <v-card>
                            <v-card-title class="headline">Czy na pewno chcesz usunąć post?</v-card-title>
                            <v-card-text>Ta operacja jest nieodwracalna.</v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn text @click="delete_confirmation = false">Anuluj</v-btn>
                                <v-btn color="error" text @click="delete_confirmation = false; deletePost(post.id)">Usuń</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
                <a class="text-decoration-none" :href="`#/post/${post.id}`"><h2>{{ post.title }}</h2></a>
                <a :href="'/#/user/'+post.user_name" class="text-decoration-none">@{{ post.user_name }}</a>  strona.com
                <v-chip x-small link :to="'/tag/'+tag.name" class="text mx-1 my-1" v-for="tag in post.tag" :key="tag.id">#{{ tag.name }}</v-chip>
                <p style="word-break: break-all">
                    {{ post.body }}
                </p>
                <p>
                    {{ post.comments.length }} komentarzy {{ post.created_at_ }}
                </p>
            </div>
        </v-col>
    </v-row>
    </div>
</template>

<script>
    export default {
        name: 'Post',
        props: ['post'],
        data() {
            return {
                edit: false,
                state: false,
                delete_confirmation: false,
                deleted: false,
                errors: []
            }
        },
        methods: {
            fetchPost: function() {
                axios.get(`/post/${this.post.id}`)
                .then(response => {
                    this.post.id = response.data.data.id;
                    this.post.title = response.data.data.title;
                    this.post.body = response.data.data.body;
                    this.post.imgurl = response.data.data.imgurl;
                    this.post.user_id = response.data.data.user_id;
                    this.post.user_name = response.data.data.user_name;
                    this.post.created_at_ = response.data.data.created_at_;
                    this.post.digs = response.data.data.digs;
                    this.post.isDig = response.data.data.isDig;
                    this.post.comments = response.data.data.comments;
                    this.post.tag = response.data.data.tag;
                });
            },
            dig: function(id) {
                axios.post(`/post/dig/${id}`)
                .then(response => {
                    this.fetchPost();
                })
                .catch(error => {
                    //
                });
            },
            unDig: function(id) {
                axios.delete(`/post/dig/${id}`)
                .then(response => {
                    this.fetchPost();
                });
            },
            updatePost: function(post) {
                axios.put(`/post/${post.id}`, post)
                .then(response => {
                    this.edit = false;
                    this.fetchPost();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
            editPost: function() {
                this.edit = true;
            },
            deletePost: function(id) {
                axios.delete(`/post/${id}`)
                .then(response => {
                    this.deleted = true;
                });
            },
            show: function() {
                this.state = true;
            },
            hide: function() {
                this.state = false;
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        }
    }
</script>

<style scoped>

</style>