<template>
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0"  v-if='this.$store.state.PassportApiToken.token'>
        <li class="nav-item">
            <router-link to='/tenants' class='nav-link'>Tenants</router-link>
        </li>
        <li class="nav-item">
            <router-link to='/employees' class='nav-link'>Employees</router-link>
        </li>

        <li class="nav-item">
            <a href='/' @click.prevent.stop='logout()' class='nav-link' title='Logout'>
                <!-- <i class="fas fa-sign-out-alt"></i> -->
                Logout
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>

<style scoped>

</style>