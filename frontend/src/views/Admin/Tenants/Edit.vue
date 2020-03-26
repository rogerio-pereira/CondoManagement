<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Tenant</strong>
        </div>

        <div class='col-8 offset-2'>
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
                tenant: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('tenants/'+this.$route.params.id)
                .then(response => {
                    this.tenant = response.data
                })
                .catch(error => {
                    console.log('Error at fetching tenant\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('tenants/'+this.tenant.id, data)
                    .then(() => {
                        this.$router.push({ name: 'tenants.index'})
                    })
                    .catch(error => {
                        console.log('Error at saving\n'+error)
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>