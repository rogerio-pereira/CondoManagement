<template>
    <div class='col-md-8 offset-md-2 '>
        <div class="card">
            <div class="card-header">
                Register
            </div>

            <div class='card-body container p-4'>
                <form-tenant :tenant='tenant' :errors='errors' @save='save($event.data)'/>
            </div>
        </div>
    </div>
</template>

<script>
    import FormTenant from '../views/Admin/Tenants/Form'

    export default {
        components: {
            FormTenant
        },
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
            save(data) {
                this.$http.post('tenants', data)
                    .then(() => {
                        this.$router.push({ name: 'login'})
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>