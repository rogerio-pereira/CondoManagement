<template>
    <div class='row'>
        <div class='col-md-12'>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            if(this.$store.state.User.user.role == 'Maintenance')
                this.$router.push({ name: 'home'})
        }
    }
</script>

<style scoped>

</style>