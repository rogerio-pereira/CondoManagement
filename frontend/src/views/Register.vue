<template>
    <div class='col-md-8 offset-md-2 '>
        <div class="card">
            <div class="card-header">
                Register
            </div>

            <div class='col-8 offset-2 my-4'>
                <div class='row'>
                    <div class="form-group col-6">
                        <label for="name">Name</label>
                        <input type="name" class="form-control" id="name" v-model='tenant.name'>

                        <div class='text-danger' v-if='errors.name'>
                            <small>
                                <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" v-model='tenant.email'>

                        <div class='text-danger' v-if='errors.email'>
                            <small>
                                <p v-for='(error, index) in errors.email' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" v-model='tenant.password'>

                        <div class='text-danger' v-if='errors.password'>
                            <small>
                                <p v-for='(error, index) in errors.password' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="password_confirmation">Confirmation</label>
                        <input type="password" class="form-control" id="password_confirmation" v-model='tenant.password_confirmation'>

                        <div class='text-danger' v-if='errors.password_confirmation'>
                            <small>
                                <p v-for='(error, index) in errors.password_confirmation' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>

                    <div class='form-group col-12 text-center'>
                        <button type="submit" class="btn btn-primary" @click.stop.prevent='register'>Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tenant: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                errors: '',
            }
        },
        methods: {
            register() {
                this.$http.post('/tenants', this.tenant)
                .then(() => {                    
                    this.$router.push({ name: 'login'})
                }) 
                .catch(error => {
                    console.log(error.response)
                    this.errors = error.response.data.errors
                });
            }
        }
    }
</script>

<style scoped>

</style>