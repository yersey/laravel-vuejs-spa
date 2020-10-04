<template>
    <div>
        <v-row class="white elevation-2">
            <v-col cols="6" sm="4" md="2" class="text-center">
                <v-avatar class="elevation-5" size="160"><v-img :src="'storage/'+profile.avatar"></v-img></v-avatar>
            </v-col>
            <v-col class="pl-md-6">
                <div class="text-right">
                    <v-btn small fab href="/#/settings"><v-icon>settings</v-icon></v-btn>
                </div>
                <p class="text-h4">{{ profile.name }}</p>
                od {{ profile.when }} na vikop
            </v-col>
        </v-row>
        <v-row class="mt-2 white elevation-1">
            <v-col cols="12">
                <p>Aktywność użytkownika</p>
                <hr>
            </v-col>
            <v-tabs v-model="tab" grow>
                <v-tab>
                    <div>
                        <div>{{ posts_count }}</div>
                        Znaleziska
                    </div>
                </v-tab>
                <v-tab>
                    <div>
                        <div>{{ entry_count }}</div>
                        Wpisy
                    </div>
                </v-tab>
                <v-tab>
                    <div>
                        <div>{{ tags.length }}</div>
                        Społeczność
                    </div>
                </v-tab>
                
            </v-tabs>
        </v-row>
        <v-row class="mt-2">
            <v-col class="white elevation-1" cols="12">
                <v-tabs-items v-model="tab">
                    <v-tab-item>
                        <post v-for="post in posts" :post="post" :key="post.id"></post>
                        <v-pagination class="mt-3" circle prev-icon="navigate_before" next-icon="navigate_next" v-model="postPagination.current_page" :length="postPagination.last_page" @input="fetchPosts"></v-pagination>
                    </v-tab-item>
                    <v-tab-item>
                        <entry v-for="entry in entries" :entry="entry" :key="entry.id"></entry>
                        <v-pagination class="mt-3" circle prev-icon="navigate_before" next-icon="navigate_next" v-model="entryPagination.current_page" :length="entryPagination.last_page" @input="fetchEntries"></v-pagination>
                    </v-tab-item>
                    <v-tab-item>
                        <p class="text-h5 mb-1">Obserowane tagi</p><hr class="mb-3">
                        <v-chip link :to="'/tag/'+tag.name" class="primary mx-1 my-1" v-for="tag in tags" :key="tag.id">#{{ tag.name }}({{ tag.followers }})</v-chip>
                    </v-tab-item>
                </v-tabs-items>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
        name: 'Post_show',
        data() {
            return {
                profile: {},
                posts: [],
                entries: [],
                tags: [],
                tab: null,
                postPagination: {
                    current_page: 1,
                    last_page: null
                },
                entryPagination: {
                    current_page: 1,
                    last_page: null
                },
                posts_count: null,
                entry_count: null,
            }
        },
        methods: {
            fetchUser: function() {
                axios.get(`/user/${this.$route.params.id}`)
                .then(response => {
                    this.profile = response.data;
                })
                .catch(error => {
                    if(error.response.status == 404){
                        this.$router.push({name: 'Not_found', params: {0: '404'}});
                    }
                });
            },
            fetchPosts: function() {
                var page_url = `/user/${this.$route.params.id}/posts?page=`+ this.postPagination.current_page;
                axios.get(page_url)
                .then(response => {
                    this.posts = response.data.data;
                    this.posts_count = response.data.meta.total;
                    this.postPagination.current_page = response.data.meta.current_page;
                    this.postPagination.last_page = response.data.meta.last_page;
                });
            },
            fetchEntries: function() {
                var page_url = `/user/${this.$route.params.id}/entries?page=`+ this.entryPagination.current_page;
                axios.get(page_url)
                .then(response => {
                    this.entries = response.data.data;
                    this.entry_count = response.data.meta.total;
                    this.entryPagination.current_page = response.data.meta.current_page;
                    this.entryPagination.last_page = response.data.meta.last_page;
                });
            },
            fetchTags: function() {
                axios.get(`/user/${this.$route.params.id}/tags`)
                .then(response => {
                    this.tags = response.data.data;
                });
            }
        },
        computed: {
            user() {
                return this.$store.getters.user;
            }
        },
        created() {
            this.fetchUser();
            this.fetchPosts();
            this.fetchEntries();
            this.fetchTags();
        },
        watch: {
            $route(to, from) {
                this.fetchUser();
                this.fetchPosts();
                this.fetchEntries();
                this.fetchTags();
            }
        },
    }
</script>

<style>

</style>