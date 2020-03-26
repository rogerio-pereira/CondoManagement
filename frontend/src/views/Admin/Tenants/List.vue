<template>
    <div>
        <h1 class='text-center'>Tenants</h1>

        <div class='text-center my-4'>
            <router-link to='/tenants/new' class='btn btn-lg btn-success'>
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
                </tr>
            </thead>
            <tbody>
                <tr v-for='(tenant, index) in tenants' :key='index'>
                    <td>
                        <router-link :to="'/tenants/edit/'+tenant.id" class='btn btn-info text-white mr-2'>
                            <i class="fas fa-edit"></i>
                        </router-link >
                        <a class='btn btn-danger text-white' @click='deleteItem(tenant.id, index)'>
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td>{{tenant.id}}</td>
                    <td>{{tenant.name}}</td>
                    <td><a :href="'mailto:'+tenant.email">{{tenant.email}}</a></td>
                    <!-- <td><a :href="'tel:'+tenant.phone">{{tenant.phone}}</a></td> -->
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tenants: []
            }
        },
        created() {
            this.$http.get('tenants').then(res => {this.tenants = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('tenants/'+id)
                    .then(() => {
                        this.tenants.splice(index, 1);
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