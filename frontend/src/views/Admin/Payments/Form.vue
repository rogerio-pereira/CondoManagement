<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='payment.id'>
        
        <div class="form-group col-md-6">
            <label for="tenant_id">Tenant</label>
            <select class="form-control" id="tenant_id" v-model='payment.tenant_id'>
                <option v-for='tenant in tenants' :key='tenant.id' :value='tenant.id'>{{tenant.name}}</option>
            </select>
            <div class='text-danger' v-if='errors.tenant_id'>
                <small>
                    <p v-for='(error, index) in errors.tenant_id' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="apartment_id">Apartment</label>
            <select class="form-control" id="apartment_id" v-model='payment.apartment_id'>
                <option v-for='apartment in apartments' :key='apartment.id' :value='apartment.id'>{{apartment.name}}</option>
            </select>
            <div class='text-danger' v-if='errors.apartment_id'>
                <small>
                    <p v-for='(error, index) in errors.apartment_id' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="installments">Installments</label>
            <input type="number" class="form-control" id="installments" v-model='payment.installments' min='1' step='1'>
            <div class='text-danger' v-if='errors.installments'>
                <small>
                    <p v-for='(error, index) in errors.installments' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="start_date">Start Date</label>
            <datetime 
                type='date' 
                input-class='form-control' 
                format='yyyy-LL-dd'
                v-model="payment.start_date" 
            />
            <div class='text-danger' v-if='errors.start_date'>
                <small>
                    <p v-for='(error, index) in errors.start_date' :key='index'>{{error}}</p>
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
    import { Datetime } from 'vue-datetime';
    import { DateTime } from "luxon";

    export default {
        components: {
            datetime: Datetime,
        },
        props: {
            payment: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        data() {
            return {
                tenants: [],
                apartment: []
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
            this.$http.get('apartments/')
                .then(response => {
                    this.apartments = response.data
                })
                .catch(error => {
                    console.log('Error at fetching apartments\n'+error)
                })
        },
        methods: {
            save(){
                this.payment.start_date = this.getDateTime(this.payment.start_date)
                this.$emit('save', {data: this.payment})
            },
            getDateTime(date) {
                if(date === null)
                    return null;

                return DateTime.fromISO(date).toFormat('yyyy-LL-dd')
            },
        }
    }
</script>

<style scoped>

</style>