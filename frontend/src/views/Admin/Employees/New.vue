<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Employee</strong>
        </div>

        <div class='card-body container p-4'>
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
                employee: {
                    id: null,
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: '',
                    role: ''
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('users', data)
                    .then(() => {
                        this.$router.push({ name: 'employees.index'})
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