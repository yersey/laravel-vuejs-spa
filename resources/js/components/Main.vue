<template>
  <div>
    <p class="green lighten-4 blue--text text--darken-4 headline">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi voluptate beatae ea magni suscipit eius placeat deleniti expedita fugit magnam inventore, quas, nihil nostrum facere ratione eum quod ad culpa.</p>
    <v-btn class="pink white--text">adasda</v-btn>
    <v-btn fab dark class="hidden-sm-only">
      <v-icon>favorite</v-icon>
    </v-btn>

    <v-btn class="success">sdf</v-btn>
    <v-btn class="error">error</v-btn>
    <v-btn class="warning">warning</v-btn>
    <v-btn class="info">info</v-btn>
    <v-btn class="primary">primary</v-btn>

    <p class="text-justify">
    Morbi mattis ullamcorper velit. Donec orci lectus, aliquam ut, faucibus non, euismod id, nulla. Fusce convallis metus id felis luctus adipiscing. Aenean massa. Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus.

    Nulla consequat massa quis enim. Praesent venenatis metus at tortor pulvinar varius. Donec venenatis vulputate lorem. Phasellus accumsan cursus velit. Pellentesque ut neque.
  </p>

    <div style="border: 3px solid red" class="py-md-5">
      <v-img :src="`https://cdn.vuetifyjs.com/images/lists/3.jpg`"></v-img>
    </div>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <v-row no-gutters justify="space-around" align="center">
      <v-col sm="2" outlined><v-card outlined>111111111</v-card></v-col>
      <v-col sm="1" outlined><v-card outlined>22222222</v-card></v-col>
      <v-col sm="1" outlined><v-card outlined>3333333333</v-card></v-col>
      <v-col sm="4" outlined>
        <v-row no-gutters>
          <v-col sm="6">
            <v-card>AAAAAAAA</v-card>
          </v-col>
          <v-col sm="9">
            <v-card>BBBBBBBB</v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

  <br><br><br><br><br><br><br><br><br>
  </div>
</template>

<script>
export default {
  name: 'Main',
  data () {
    return {
      msg: 'One'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
