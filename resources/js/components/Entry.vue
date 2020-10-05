<template>
    <div v-if="!deleted">
        <v-row class="white elevation-1" @mouseover="show" @mouseout="hide" >
            <v-col cols="2" md="1" class="text-center">
                <v-avatar class="elevation-1" size="60"><v-img :src="'storage/'+entry.user_avatar"></v-img></v-avatar>
            </v-col>
            <v-col cols="10" md="11" v-if="edit" class="pl-5 pl-sm-2">
                <v-textarea :error-messages="errors" auto-grow rows="1" v-model="entry.body"></v-textarea>
                <v-btn x-small class="primary" @click="updateEntry()">zapisz</v-btn>
                <v-btn x-small text @click="edit = false">anuluj</v-btn>
            </v-col>
            <v-col cols="10" md="11" v-else class="pl-5 pl-sm-2">
                <a :href="'/#/user/'+entry.user_name" class="text-decoration-none">{{ entry.user_name }}</a> 
                <a :href="`#/mirko/${entry.id}`" class="text-decoration-none">{{ entry.when }}</a>
                <div class="float-right green--text">
                    {{ entry.pluses }}
                    <a v-if="entry.user_id != user.id && user.id">
                        <v-icon v-if="entry.isPlus" @click="unPlus(entry.id)" class="mb-1 green--text">add_box</v-icon>
                        <v-icon v-else @click="plus(entry.id)" class="mb-1 green--text">add</v-icon>
                    </a> 
                </div>
                <hr class="mb-1">
                <p style="word-break: break-all" class="mb-0 pb-0" v-html="bodyReplaced(entry.body)"></p>
                <transition name="fade">
                    <div v-show="state" v-if="user.id" style="position: absolute; margin-top: -5px; margin-left: -10px">
                        <v-btn x-small text @click="$emit('odpowiedz', entry)">Odpowiedz</v-btn>
                        <a v-if="entry.user_id == user.id">
                            <v-btn x-small text class="warning--text" @click="edit = true">Edytuj</v-btn>
                            <v-btn x-small text class="error--text" @click="deleteEntry()">Usuń</v-btn>
                        </a>
                    </div>
                </transition>
            </v-col>
            <slot></slot>
        </v-row>   
    </div>
</template>

<script>
    export default {
        name: 'Entry',
        props: ['entry'],
        data() {
            return {
                edit: false,
                state: false,
                deleted: false,
                errors : []
            }
        },
        methods: {
            fetchEntry: function() {
                axios.get(`/mirko/${this.entry.id}`)
                .then(response => {
                    this.entry.id = response.data.data.id;
                    this.entry.body = response.data.data.body;
                    this.entry.user_id = response.data.data.user_id;
                    this.entry.user_name = response.data.data.user_name;
                    this.entry.when = response.data.data.when;
                    this.entry.pluses = response.data.data.pluses;
                    this.entry.isPlus = response.data.data.isPlus;
                    this.entry.comments = response.data.data.comments;
                    this.entry.user_avatar = response.data.data.user_avatar;
                });
            },
            plus: function(id) {
                axios.post(`/entry/plus/${id}`)
                .then(response => {
                    this.fetchEntry();
                });
                
            },
            unPlus: function(id) {
                axios.delete(`/entry/plus/${id}`)
                .then(response => {
                    this.fetchEntry();
                });
            },
            updateEntry: function() {
                axios.put(`/mirko/${this.entry.id}`, this.entry)
                .then(response => {
                    this.edit = false;
                    this.fetchEntry();
                })
                .catch(error => {
                    this.errors = error.response.data.errors.body;
                });
            },
            editEntry: function() {
                this.edit = true;
            },
            deleteEntry: function() {
                axios.delete(`/mirko/${this.entry.id}`)
                .then(response => {
                    this.deleted = true;
                });
            },
            show: function(id) {
                this.state = true;
            },
            hide: function() {
                this.state = false;
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
            }
        }
    }
</script>