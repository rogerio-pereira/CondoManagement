<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Employee</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-employee :employee='employee' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormEmployee from './Form'

    export default {
        components: {
            FormEmployee
        },
        data() {
            return {
                employee: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('users/'+this.$route.params.id)
                .then(response => {
                    this.employee = response.data
                })
                .catch(error => {
                    console.log('Error at fetching employee\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('users/'+this.employee.id, data)
                    .then(() => {
                        this.$router.push({ name: 'employees.index'})
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