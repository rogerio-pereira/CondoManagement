<template>
    <div id="app">
        <menu-app />

        <div class='row content my-3 mb-5 contentContainer'>
            <div class='container'>
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode='out-in'>
                    <router-view/>
                </transition>
            </div>
        </div>

        <footer-app />
    </div>
</template>

<script>
    import MenuApp from './components/template/Menu'
    import FooterApp from './components/template/Footer'

    export default {
        components: {
            MenuApp,
            FooterApp
        },
        data() {
            return {
                user: null
            }
        },
        created() {
            if(this.$route.name != 'register') {
                if(this.$store.state.PassportApiToken.token) {
                    this.$http.defaults.headers.common['Authorization'] = 'Bearer '+this.$store.state.PassportApiToken.token
                }
                else {
                    this.$router.push({ name: 'login'})
                }
            }
        }
    }
</script>

<style>
    * {
        font-family: 'Lato', sans-serif;
    }

    body {
        background: #f2e5cb !important;

        overflow-x: hidden;
        font-size: 1.4rem !important;
    }

    h1 {
        font-size: 2.2em;
        font-weight: 700 !important;
    }

    div.contentContainer {
        z-index: 0;
    }

    div.content{
        min-height: 500px;
    }

    .btn-primary {
        background-color: #3E5151 !important;
        border-color: #3E5151 !important;
    }

    .btn-primary:hover {
        background-color: #223534 !important;
        border-color: #223534 !important;
    }

    .card {
        background-color: #f9f3e5 !important;
        border: none !important;
        
        -webkit-box-shadow: 1px 1px 3px 1px rgba(62,81,81,0.4);
        -moz-box-shadow: 1px 1px 3px 1px rgba(62,81,81,0.4);
        box-shadow: 1px 1px 3px 1px rgba(62,81,81,0.4);
    }

    .card-header {
        border-bottom: 1px solid #f2e5cb !important;
    }
</style>
