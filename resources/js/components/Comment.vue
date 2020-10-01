<template>
    <div v-if="!deleted">
        <v-row class="white pb-2" @mouseover="show(comment.id)" @mouseout="hide" >
            <v-col cols="2" md="1" class="text-center">
                <v-avatar size="60"><v-img :src="'storage/'+comment.user_avatar"></v-img></v-avatar>
            </v-col>
            <v-col cols="10" md="11" v-if="comment.id == edit_id" class="pl-5 pl-sm-2">
                <v-textarea auto-grow rows="1" v-model="comment.body"></v-textarea>
                <v-btn x-small class="primary" @click="updateComment(comment)">zaktualizuj</v-btn>
                <v-btn x-small text @click="edit_id = null">anuluj</v-btn>
            </v-col>
            <v-col cols="10" md="11" v-else class="pl-5 pl-sm-2">
                <a :href="'/#/user/'+comment.user_name" class="text-decoration-none">{{ comment.user_name }}</a> 
                <a>{{ comment.created_at_ }}</a>
                <div class="float-right green--text">
                    {{ comment.pluses }} 
                    <a v-if="comment.user_id != user.id && user.id">
                        <v-icon v-if="comment.isPlus" @click="unPlus(comment.id)" class="mb-1 green--text">add_box</v-icon>
                        <v-icon v-else @click="plus(comment.id)" class="mb-1 green--text">add</v-icon>
                    </a>
                </div>
                <hr class="mb-1">
                <p style="word-break: break-all" class="mb-0 pb-0" v-html="bodyReplaced(comment.body)"></p>
                <transition name="fade">
                    <div v-show="state_id == comment.id" v-if="user.id" style="position: absolute; margin-top: -5px; margin-left: -10px">
                        <v-btn x-small text @click="reply.state = 1; reply.body += '@'+comment.user_name+': '">Odpowiedz</v-btn>
                        <a v-if="comment.user_id == user.id">
                            <v-btn x-small text class="warning--text" @click="edit_id = comment.id">Edytuj</v-btn>
                            <v-btn x-small text class="error--text" @click="deleteComment(comment)">Usuń</v-btn>
                        </a>
                    </div>
                </transition>
            </v-col>
        </v-row>
        <v-row v-for="comment in comment.comments" :key="comment.id" class="white" @mouseover="show(comment.id)" @mouseout="hide">
            <v-col cols="1"></v-col>
            <v-col class="py-1">
                <v-row>
                    <v-col cols="2" md="1" class="text-center">
                        <v-avatar size="60"><v-img :src="'storage/'+comment.user_avatar"></v-img></v-avatar>
                    </v-col>
                    <v-col cols="10" md="11" v-if="comment.id == edit_id">
                        <v-textarea auto-grow rows="1" v-model="comment.body"></v-textarea>
                        <v-btn x-small class="primary" @click="updateComment(comment)">zapisz</v-btn>
                        <v-btn x-small text @click="edit_id = null">anuluj</v-btn>
                    </v-col>
                    <v-col cols="10" md="11" v-else>
                        <a :href="'/#/user/'+comment.user_name" class="text-decoration-none">{{ comment.user_name }}</a> 
                        <a>{{ comment.created_at_ }}</a>
                        <div class="float-right green--text">
                            {{ comment.pluses }} 
                            <a v-if="comment.user_id != user.id && user.id">
                                <v-icon v-if="comment.isPlus" @click="unPlus(comment.id)" class="mb-1 green--text">add_box</v-icon>
                                <v-icon v-else @click="plus(comment.id)" class="mb-1 green--text">add</v-icon>
                            </a>
                        </div>
                        <hr class="mb-1">
                        <p style="word-break: break-all" class="mb-0" v-html="bodyReplaced(comment.body)"></p>
                        <transition name="fade">
                            <div v-show="state_id == comment.id" v-if="user.id" style="position: absolute; margin-top: -5px; margin-left: -10px">
                                <v-btn x-small text @click="reply.state = 1; reply.body += '@'+comment.user_name+': '">Odpowiedz</v-btn>
                                <a v-if="comment.user_id == user.id">
                                    <v-btn x-small text class="warning--text" @click="edit_id = comment.id">Edytuj</v-btn>
                                    <v-btn x-small text class="error--text" @click="deleteComment(comment, true)">Usuń</v-btn>
                                </a>
                            </div>
                        </transition>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>

        <v-row class="white" v-if="reply.state && user.id">
            <v-col cols="1"></v-col>
            <v-col class="py-1 pb-0">
                <v-row>
                    <v-col cols="2" md="1" class="text-center">
                        <v-avatar size="60"><v-img :src="'storage/'+user.avatar"></v-img></v-avatar>
                    </v-col>
                    <v-col cols="10" md="11">
                        <v-textarea label="Treść komentarza" auto-grow rows="1" v-model="reply.body"></v-textarea>
                        <v-btn small class="primary" @click="addReply">Zapisz</v-btn>
                        <v-btn small text @click="reply.body = null; reply.state = false;">Anuluj</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    export default {
        name: 'Comment',
        props: ['comment'],
        data() {
            return {
                edit_id: null,
                state_id: 1,
                reply: {
                    state: false,
                    id: this.comment.id,
                    body: '',
                    model: 'comment'
                },
                deleted: false
            }
        },
        methods: {
            fetchComment: function() {
                axios.get(`/comment/${this.comment.id}`)
                .then(response => {
                    this.comment.id = response.data.data.id;
                    this.comment.body = response.data.data.body;
                    this.comment.user_id = response.data.data.user_id;
                    this.comment.user_name = response.data.data.user_name;
                    this.comment.created_at_ = response.data.data.created_at_;
                    this.comment.pluses = response.data.data.pluses;
                    this.comment.isPlus = response.data.data.isPlus;
                    this.comment.comments = response.data.data.comments;
                });
            },
            plus: function(id) {
                axios.post('/plus', {id: id, model: 'comment'})
                .then(response => {
                    this.fetchComment();
                });
                
            },
            unPlus: function(id) {
                axios.delete(`/plus/comment/${id}`)
                .then(response => {
                    this.fetchComment();
                });
            },
            updateComment: function(comment) {
                axios.put(`/comment/${comment.id}`, comment)
                .then(response => {
                    this.edit_id = null;
                    this.fetchComment();
                });
            },
            editComment: function(id) {
                this.edit_id = id;
            },
            deleteComment: function(comment, reply = false) {
                axios.delete(`/comment/${comment.id}`)
                .then(response => {
                    if(reply) {
                        this.fetchComment();
                    }else{
                        this.deleted = true
                    }
                });
            },
            show: function(id) {
                this.state_id = id;
            },
            hide: function() {
                this.state_id = null;
            },
            addReply: function() {
                axios.post('/comment', this.reply)
                .then(response => {
                    this.fetchComment();
                    this.reply.body = null;
                    this.reply.state = false;
                });
            },
            bodyReplaced: function(text) {
                if(text){
                    return text.split(' ').map(w => {
                        w = w.startsWith('@') ? `<a href="/#/user/${w.slice(1)}">${w}</a>` : w;
                        return w.startsWith('#') ? `<a href="/#/tag/${w.slice(1)}">${w}</a>` : w;
                    }).join(' ');
                }
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            },
        }
    }
</script>

<style scoped>

</style>