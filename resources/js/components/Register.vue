<template>
  <div>
    <v-row justify="center">
        <div v-if="success">
            <v-alert type="success" icon="how_to_reg">Zarejestrowano</v-alert>
        </div>
        <v-col cols="12" sm="8" md="7" lg="5" v-else>
            <v-card>
                <v-form>
                    <v-card-text>
                        <v-text-field :error-messages="errors.name" v-model="user.name" label="Name" name="name" placeholder="Jan"></v-text-field>
                        <v-text-field :error-messages="errors.email" v-model="user.email" label="Email" name="email" placeholder="test@email.com"></v-text-field>
                        <v-text-field :error-messages="errors.password" v-model="user.password" type="password" label="Hasło" placeholder="********"></v-text-field>
                        <v-text-field :error-messages="errors.password" v-model="user.password_confirmation" type="password" label="Powtórz hasło" placeholder="********"></v-text-field>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions class="px-3 pb-3">
                        <v-btn class="primary" @click="register">Zarejestruj</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn class="float-right" @click="reset">Anuluj</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
    name: 'Register',
    data () {
        return {
            user: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            errors: {},
            success: false,
        }
    },
    methods: {
        reset: function(){
            this.user = {};
            this.errors = {};
        },
        register: function() {
            this.$store.dispatch('register', this.user)
            .then(response => {
                this.success = true;
                this.reset();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
