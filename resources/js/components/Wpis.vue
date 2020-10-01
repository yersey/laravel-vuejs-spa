<template>
    <div v-if="!deleted">
        <v-row class="white elevation-1" @mouseover="show" @mouseout="hide" >
            <v-col cols="2" md="1" class="text-center">
                <v-avatar class="elevation-1" size="60"><v-img :src="'storage/'+wpis.user_avatar"></v-img></v-avatar>
            </v-col>
            <v-col cols="10" md="11" v-if="edit" class="pl-5 pl-sm-2">
                <v-textarea :error-messages="errors" auto-grow rows="1" v-model="wpis.body"></v-textarea>
                <v-btn x-small class="primary" @click="updateWpis()">zapisz</v-btn>
                <v-btn x-small text @click="edit = false">anuluj</v-btn>
            </v-col>
            <v-col cols="10" md="11" v-else class="pl-5 pl-sm-2">
                <a :href="'/#/user/'+wpis.user_name" class="text-decoration-none">{{ wpis.user_name }}</a> 
                <a :href="`#/mirko/${wpis.id}`" class="text-decoration-none">{{ wpis.created_at_ }}</a>
                <div class="float-right green--text">
                    {{ wpis.pluses }}
                    <a v-if="wpis.user_id != user.id && user.id">
                        <v-icon v-if="wpis.isPlus" @click="unPlus" class="mb-1 green--text">add_box</v-icon>
                        <v-icon v-else @click="plus" class="mb-1 green--text">add</v-icon>
                    </a> 
                </div>
                <hr class="mb-1">
                <p style="word-break: break-all" class="mb-0 pb-0" v-html="bodyReplaced(wpis.body)"></p>
                <transition name="fade">
                    <div v-show="state" v-if="user.id" style="position: absolute; margin-top: -5px; margin-left: -10px">
                        <v-btn x-small text @click="$emit('odpowiedz', wpis)">Odpowiedz</v-btn>
                        <a v-if="wpis.user_id == user.id">
                            <v-btn x-small text class="warning--text" @click="edit = true">Edytuj</v-btn>
                            <v-btn x-small text class="error--text" @click="deleteWpis()">Usuń</v-btn>
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
        name: 'Wpis',
        props: ['wpis'],
        data() {
            return {
                edit: false,
                state: false,
                deleted: false,
                errors : []
            }
        },
        methods: {
            fetchWpis: function() {
                axios.get(`/mirko/${this.wpis.id}`)
                .then(response => {
                    this.wpis.id = response.data.data.id;
                    this.wpis.body = response.data.data.body;
                    this.wpis.user_id = response.data.data.user_id;
                    this.wpis.user_name = response.data.data.user_name;
                    this.wpis.created_at_ = response.data.data.created_at_;
                    this.wpis.pluses = response.data.data.pluses;
                    this.wpis.isPlus = response.data.data.isPlus;
                    this.wpis.comments = response.data.data.comments;
                    this.wpis.user_avatar = response.data.data.user_avatar;
                });
            },
            plus: function() {
                axios.post('/plus', {id: this.wpis.id, model: 'wpis'})
                .then(response => {
                    this.fetchWpis();
                });
                
            },
            unPlus: function() {
                axios.delete(`plus/wpis/${this.wpis.id}`)
                .then(response => {
                    this.fetchWpis();
                });
            },
            updateWpis: function() {
                axios.put(`/mirko/${this.wpis.id}`, this.wpis)
                .then(response => {
                    this.edit = false;
                    this.fetchWpis();
                })
                .catch(error => {
                    this.errors = error.response.data.errors.body;
                });
            },
            editWpis: function() {
                this.edit = true;
            },
            deleteWpis: function() {
                axios.delete(`/mirko/${this.wpis.id}`)
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