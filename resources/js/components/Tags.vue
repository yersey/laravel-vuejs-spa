<template>
    <v-row>
        <v-col class="white">
            <v-chip link :to="'tag/'+tag.name" class="primary mx-1 my-1" v-for="tag in orderedTags" :key="tag.id">#{{ tag.name }}({{ tag.followers }})</v-chip>
        </v-col>
    </v-row>
</template>

<script>
name
export default {
    name: 'Tags',
    data() {
        return {
            tags: [],
        }
    },
    methods: {
        fetchTags: function() {
            axios.get('/tags')
            .then(response => {
                this.tags = response.data;
            });
        },
    },
    computed: {
        user() {
            return this.$store.getters.user;
        }
    },
    created() {
        this.fetchTags();
    },
    computed: {
        orderedTags: function () {
            return _.orderBy(this.tags, 'followers', 'desc')
    }
  }
}
</script>

<style>

</style>