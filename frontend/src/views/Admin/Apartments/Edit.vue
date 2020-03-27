<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Apartment</strong>
        </div>

        <div class='col-8 offset-2'>
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
                apartment: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('apartments/'+this.$route.params.id)
                .then(response => {
                    this.apartment = response.data
                })
                .catch(error => {
                    console.log('Error at fetching apartment\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('apartments/'+this.apartment.id, data)
                    .then(() => {
                        this.$router.push({ name: 'apartments.index'})
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