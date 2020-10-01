<template>
    <v-row justify="center">
        <v-col cols="12" sm="8" md="7" lg="5">
            <v-card>
                <v-form>
                    <v-card-text>
                        <v-text-field :error="error" v-model="credential.email" label="Email" name="email" placeholder="test@email.com"></v-text-field>
                        <v-text-field :error="error" v-model="credential.password" type="password" label="Hasło" placeholder="********"></v-text-field>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions class="px-3 pb-3">
                        <v-btn class="primary" @click="login">Zaloguj</v-btn>
                        <v-btn @click="resetForm">Anuluj</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn class="warning" to="register">Rejestracja</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: 'Login',
        data() {
            return {
                credential: {
                    email:'',
                    password:''
                },
                error: false
            }
        },
        methods: {
            login() {
                this.$store.dispatch('login', this.credential)
                .catch(error => {
                    this.error = true;
                });
                this.resetForm();
            },
            resetForm() {
                this.credential = {
                    name: null,
                    password: null
                },
                this.error = false;
            }
        }
    }
</script>