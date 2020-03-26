<template>
    <div class="row navbarContainer">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <router-link to='/' class='navbar-brand'>Condo Manager</router-link>
                <button class="navbar-toggler btn btn-light" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
                    <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0"  v-if='this.$store.state.PassportApiToken.token'>
                            <menu-admin v-if="this.$store.state.User.user.role == 'Admin'" />
                            <menu-maitenance v-if="this.$store.state.User.user.role == 'Maintenance'" />
                            <menu-tenant v-if="this.$store.state.User.user.role == 'Tenant'" />

                            <li class="nav-item">
                                <a href='/' @click.prevent.stop='logout()' class='nav-link' title='Logout'>
                                    <!-- <i class="fas fa-sign-out-alt"></i> -->
                                    Logout
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0"  v-else>
                            <li class="nav-item">
                                <router-link to='/register' class='nav-link'>
                                    Register
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to='/login' class='nav-link'>
                                    Login
                                </router-link>
                            </li>
                        </ul>
                    </transition>
                </div>
            </nav>
        </div>
    </div>     
</template>

<script>
    import MenuAdmin from './MenuAdmin'
    import MenuMaitenance from './MenuMaitenance'
    import MenuTenant from './MenuTenant'

    export default {
        components: {
            MenuAdmin,
            MenuMaitenance,
            MenuTenant,
        },
        data() {
            return {
            }
        },
        methods: {
            logout() {
                this.$store.commit('setToken', null)
                this.$store.commit('setUser', null)
                this.$http.defaults.headers.common['Authorization'] = null
                this.$router.push({ name: 'login'})
            }
        },
    }
</script>

<style>
    .navbarContainer
    {
        background: #3E5151;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to top, #f2e5cb, #3E5151);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to top, #f2e5cb, #3E5151); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        z-index: 10;
    }

    .navbar a,
    .navbar li a
    {
        color:white;
        font-size: 1em;
    }

    .navbar a:hover,
    .navbar li a:hover{
        color: white;
        text-decoration: underline;
    }

    button.navbar-toggler {
        background-color: white;
    }
</style>