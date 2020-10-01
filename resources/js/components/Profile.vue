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
                od 2 tyg. na vikop
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
                        <div>{{ wpis_count }}</div>
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
                        <wpis v-for="wpis in wpisy" :wpis="wpis" :key="wpis.id"></wpis>
                        <v-pagination class="mt-3" circle prev-icon="navigate_before" next-icon="navigate_next" v-model="wpisPagination.current_page" :length="wpisPagination.last_page" @input="fetchWpisy"></v-pagination>
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
                wpisy: [],
                tags: [],
                tab: null,
                postPagination: {
                    current_page: 1,
                    last_page: null
                },
                wpisPagination: {
                    current_page: 1,
                    last_page: null
                },
                posts_count: null,
                wpis_count: null,
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
            fetchWpisy: function() {
                var page_url = `/user/${this.$route.params.id}/wpisy?page=`+ this.wpisPagination.current_page;
                axios.get(page_url)
                .then(response => {
                    this.wpisy = response.data.data;
                    this.wpis_count = response.data.meta.total;
                    this.wpisPagination.current_page = response.data.meta.current_page;
                    this.wpisPagination.last_page = response.data.meta.last_page;
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
            this.fetchWpisy();
            this.fetchTags();
        },
        watch: {
            $route(to, from) {
                this.fetchUser();
                this.fetchPosts();
                this.fetchWpisy();
                this.fetchTags();
            }
        },
    }
</script>

<style>

</style>