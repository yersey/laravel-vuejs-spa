<template>
    <div>
        <v-row v-if="user.id" class="white rounded-t-lg elevation-1">
            <v-col cols="8" sm="10" lg="10">
                <v-textarea :error-messages="errors" rows="1" v-model="entry.body" label="Treść wpisu..."></v-textarea>
            </v-col>
            <v-col cols="4" sm="2" lg="2" class="text-center">
                <v-btn class="primary mt-5" @click="addEntry">Dodaj</v-btn>
            </v-col>
        </v-row>
        
        <entry @odpowiedz="new_comment_id = $event.id" class="mt-3" v-for="entry in entries" :key="entry.id" :entry="entry">
            <v-col offset="1" cols="11">
                <comment v-for="comment in entry.top_comments" :key="comment.id" :comment="daniel(comment)"></comment>
            </v-col>
            <v-col class="py-0">
                <v-row class="white" v-if="user.id && new_comment_id == entry.id">
                    <v-col cols="2" md="1" class="text-center pt-0">
                        <v-avatar size="60"><v-img :src="'storage/'+user.avatar"></v-img></v-avatar>
                    </v-col>
                    <v-col cols="10" md="11" class="pt-0">
                        <v-textarea label="Treść komentarza" auto-grow rows="1" v-model="comment.body"></v-textarea>
                        <v-btn small class="primary" @click="addComment">Zapisz</v-btn>
                        <v-btn small text @click="comment.body = ''">Anuluj</v-btn>
                    </v-col>
                </v-row>
            </v-col>
        </entry>

        <v-pagination class="mt-3" circle prev-icon="navigate_before" next-icon="navigate_next" v-model="pagination.current_page" :length="pagination.last_page" @input="fetchEntries"></v-pagination>

    </div>
</template>

<script>
    export default {
        name: 'Entries',
        data() {
            return {
                entries: [],
                entry: {
                    body: null
                },
                comment: {
                    id: null,
                    body: '',
                    model: 'entry'
                },
                new_comment_id: null,
                errors: [],
                pagination: {
                    current_page: 1,
                    last_page: null
                },
            }
        },
        methods: {
            fetchEntries: function() {
                var page_url = '/mirko?page='+ this.pagination.current_page;
                axios.get(page_url)
                .then(response => {
                    this.entries = response.data.data;
                    this.pagination.current_page = response.data.meta.current_page;
                    this.pagination.last_page = response.data.meta.last_page;
                });
            },
            addEntry: function() {
                axios.post('/mirko', this.entry)
                .then(response => {
                    this.fetchEntries();
                    this.clearForm();
                })
                .catch(error => {
                    this.errors = error.response.data.errors.body;
                });
                
            },
            clearForm: function() {
                this.entry.body = null;
            },
            addComment: function() {
                this.comment.id = this.new_comment_id;
                axios.post('/comment', this.comment)
                .then(response => {
                    this.fetchEntries();
                    this.comment.body = null;
                });
            },
            daniel: function(comment) {
                comment.comments = null;
                return comment;
            },
        },
        created() {
            this.fetchEntries();
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        }
    }
</script>

