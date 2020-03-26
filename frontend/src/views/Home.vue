<template>
<div>
    <div class="card">
        <div class='my-4'>
            <div class='container'>
                <h1>Welcome {{user.name}}</h1>
                dasdas
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                user: this.$store.state.User.user
            }
        },
    }
</script>

<style scoped>
</style>