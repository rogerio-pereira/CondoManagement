<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Payment</strong>
        </div>

        <div class='card-body container p-4'>
            <form-payment :payment='payment' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormPayment from './Form'

    export default {
        components: {
            FormPayment
        },
        data() {
            return {
                payment: {
                    id: null,
                    tenant_id: '',
                    apartment_id: '',
                    installments: 1,
                    start_date: '',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('payments', data)
                    .then(() => {
                        this.$router.push({ name: 'payments.index'})
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