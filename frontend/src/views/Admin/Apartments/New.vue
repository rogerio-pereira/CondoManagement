<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Apartment</strong>
        </div>

        <div class='card-body container p-4'>
            <form-apartment :apartment='apartment' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormApartment from './Form'

    export default {
        components: {
            FormApartment
        },
        data() {
            return {
                apartment: {
                    name: '',
                    price: '',
                    occupied: 0,
                    tenant_id: '',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('apartments', data)
                    .then(() => {
                        this.$router.push({ name: 'apartments.index'})
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