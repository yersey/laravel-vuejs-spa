<template>
    <div>
        <v-row class="white mb-4">
            <v-col class="text-h4 primary--text">
                #{{ $route.params.name }}({{ followers }})
                <v-btn v-if="isFollow" class="text float-right" @click="unFollow(name)">Przestań obserwować</v-btn>
                <v-btn v-else class="primary float-right" @click="follow(name)">Obserwuj</v-btn>
            </v-col>
        </v-row>
        <div v-for="item in items" :key="item.id" class="mt-2">
            <post v-if="item.imgurl" :post="item"></post>
            <entry v-else :entry="item"></entry>
        </div>
    </div>
</template>

<script>
name
export default {
    name: 'Tag',
    data() {
        return {
            items: [],
            name: null,
            followers: null,
            isFollow: null
        }
    },
    methods: {
        fetchItems: function() {
            axios.get(`/tag/${this.$route.params.name}`)
            .then(response => {
                this.items = response.data.data;
                this.name = response.data.tag.name;
                this.followers = response.data.tag.followers;
                this.isFollow = response.data.tag.isfollow;
            });
        },
        follow: function(name) {
            axios.post(`/tag/${name}/follow`)
            .then(response => {
                this.isFollow = true;
                this.followers += 1;
            });
            
        },
        unFollow: function(name) {
            axios.delete(`/tag/${name}/unfollow`)
            .then(response => {
                this.isFollow = false;
                this.followers -= 1;
            });
        },
    },
    computed: {
        user() {
            return this.$store.getters.user;
        }
    },
    created() {
        this.fetchItems();
    },
    watch: {
        $route(to, from) {
            this.fetchItems();
        }
    }
}
</script>

<style>

</style>