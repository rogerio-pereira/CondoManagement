<template>
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0"  v-if='this.$store.state.PassportApiToken.token'>
        <li class="nav-item">
            Maitenance
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