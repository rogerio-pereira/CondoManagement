<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Tenant</strong>
        </div>

        <div class='card-body container p-4'>
            <form-tenant :tenant='tenant' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormTenant from './Form'

    export default {
        components: {
            FormTenant
        },
        data() {
            return {
                tenant: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: '',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('tenants', data)
                    .then(() => {
                        this.$router.push({ name: 'tenants.index'})
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