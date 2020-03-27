<template>
    <div>
        <h1 class='text-center'>Employees</h1>

        <div class='text-center my-4'>
            <router-link to='/employees/new' class='btn btn-lg btn-success'>
                <i class="fas fa-plus-circle"></i> &nbsp; New
            </router-link>
        </div>


        <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th width='120px'></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='(employee, index) in employees' :key='index'>
                    <td>
                        <router-link :to="'/employees/edit/'+employee.id" class='btn btn-info text-white mr-2'>
                            <i class="fas fa-edit"></i>
                        </router-link >
                        <a class='btn btn-danger text-white' @click='deleteItem(employee.id, index)'>
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td>{{employee.id}}</td>
                    <td>{{employee.name}}</td>
                    <td><a :href="'mailto:'+employee.email">{{employee.email}}</a></td>
                    <td><a :href="'tel:'+employee.phone">{{employee.phone}}</a></td>
                    <td>{{employee.role}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                employees: []
            }
        },
        created() {
            this.$http.get('users').then(res => {this.employees = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('users/'+id)
                    .then(() => {
                        this.employees.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>