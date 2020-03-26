import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

import TenantIndex from '../views/Admin/Tenants/Index.vue'
import TenantList from '../views/Admin/Tenants/List.vue'
import TenantNew from '../views/Admin/Tenants/New.vue'
import TenantEdit from '../views/Admin/Tenants/Edit.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/tenants',
    component: TenantIndex,
    children: [
      {path: '', component: TenantList, props: true, name: 'tenants.index'},
      {path: 'new', component: TenantNew, props: true, name: 'tenants.new'},
      {path: 'edit/:id', component: TenantEdit, props: true, name: 'tenants.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router