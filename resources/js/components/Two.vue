<template>
  <div class="hello">
    <entry :entry="{ id: 1, body: 'testowy wpis', user_id: 1, user_name: 'Daniel', when: 123, pluses: 7, isPlus: true, comments: [] }"></entry>
  </div>
</template>

<script>
export default {
  name: 'Two',
  data () {
    return {
      msg: 'Two'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
