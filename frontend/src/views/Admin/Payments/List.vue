<template>
    <div>
        <h1 class='text-center'>Payments</h1>

        <div class='text-center my-4'>
            <router-link to='/payments/new' class='btn btn-lg btn-success'>
                <i class="fas fa-plus-circle"></i> &nbsp; New
            </router-link>
        </div>


        <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
            <thead>
                <tr>
                    <!-- <th width='120px'></th> -->
                    <th>ID</th>
                    <th>Tenant</th>
                    <th>Apartment</th>
                    <th>Due at</th>
                    <th>Payed</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='(payment, index) in payments' :key='index'>
                    <!-- <td>
                        <router-link :to="'/payments/edit/'+payment.id" class='btn btn-info text-white mr-2'>
                            <i class="fas fa-edit"></i>
                        </router-link >
                        <a class='btn btn-danger text-white' @click='deleteItem(payment.id, index)'>
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td> -->
                    <td>{{payment.id}}</td>
                    <td>{{payment.tenant.name}}</td>
                    <td>{{payment.apartment.name}}</td>
                    <td>{{payment.due_at}}</td>
                    <td>
                        <span v-if='payment.payed == true'><i class="fas fa-check"></i></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                payments: []
            }
        },
        created() {
            this.$http.get('payments').then(res => {this.payments = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('payments/'+id)
                    .then(() => {
                        this.payments.splice(index, 1);
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