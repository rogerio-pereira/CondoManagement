<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='apartment.id'>
        
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='apartment.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" v-model='apartment.price' min='0.01' step='0.01'>
            <div class='text-danger' v-if='errors.price'>
                <small>
                    <p v-for='(error, index) in errors.price' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="occupied">Occupied?</label>
            <select class="form-control" id="occupied" v-model='apartment.occupied' @change="apartmentOccupied">
                <option value='0'>No</option>
                <option value='1'>Yes</option>
            </select>
            <div class='text-danger' v-if='errors.occupied'>
                <small>
                    <p v-for='(error, index) in errors.occupied' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="tenant_id">Tenant</label>
            <select class="form-control" id="tenant_id" v-model='apartment.tenant_id' :disabled="apartment.occupied == 0">
                <option v-for='tenant in tenants' :key='tenant.id' :value='tenant.id'>{{tenant.name}}</option>
            </select>
            <div class='text-danger' v-if='errors.tenant_id'>
                <small>
                    <p v-for='(error, index) in errors.tenant_id' :key='index'>{{error}}</p>
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
            apartment: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        data() {
            return {
                tenants: []
            }
        },
        created() {
            this.$http.get('tenants/')
                .then(response => {
                    this.tenants = response.data
                })
                .catch(error => {
                    console.log('Error at fetching tenants\n'+error)
                })
        },
        methods: {
            save(){
                this.$emit('save', {data: this.apartment})
            },
            apartmentOccupied()
            {
                if(this.apartment.occupied == 0)
                    this.apartment.tenant_id = null
            }
        }
    }
</script>

<style scoped>

</style>