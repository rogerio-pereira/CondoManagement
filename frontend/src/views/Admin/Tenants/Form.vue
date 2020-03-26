<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='tenant.id'>
        
        <div class="form-group col-md-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='tenant.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" v-model='tenant.email'>
            <div class='text-danger' v-if='errors.email'>
                <small>
                    <p v-for='(error, index) in errors.email' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" v-model='tenant.phone'>
            <div class='text-danger' v-if='errors.phone'>
                <small>
                    <p v-for='(error, index) in errors.phone' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" v-model='tenant.password'>

            <div class='text-danger' v-if='errors.password'>
                <small>
                    <p v-for='(error, index) in errors.password' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-6">
            <label for="password_confirmation">Confirmation</label>
            <input type="password" class="form-control" id="password_confirmation" v-model='tenant.password_confirmation'>

            <div class='text-danger' v-if='errors.password_confirmation'>
                <small>
                    <p v-for='(error, index) in errors.password_confirmation' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='form-group text-center col-md-12 mt-4'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>
                <i class="far fa-save mr-2"></i> Save
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            tenant: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        /*data() {
            return {
                tenant: {
                    id: null,
                    name: '',
                    initialBalance: 0
                }
            }
        },*/
        methods: {
            save(){
                this.$emit('save', {data: this.tenant})
            }
        }
    }
</script>

<style scoped>

</style>