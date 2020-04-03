import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

import TenantIndex from '../views/Admin/Tenants/Index.vue'
import TenantList from '../views/Admin/Tenants/List.vue'
import TenantNew from '../views/Admin/Tenants/New.vue'
import TenantEdit from '../views/Admin/Tenants/Edit.vue'

import EmployeeIndex from '../views/Admin/Employees/Index.vue'
import EmployeeList from '../views/Admin/Employees/List.vue'
import EmployeeNew from '../views/Admin/Employees/New.vue'
import EmployeeEdit from '../views/Admin/Employees/Edit.vue'

import ApartmentIndex from '../views/Admin/Apartments/Index.vue'
import ApartmentList from '../views/Admin/Apartments/List.vue'
import ApartmentNew from '../views/Admin/Apartments/New.vue'
import ApartmentEdit from '../views/Admin/Apartments/Edit.vue'

import PaymentIndex from '../views/Admin/Payments/Index.vue'
import PaymentList from '../views/Admin/Payments/List.vue'
import PaymentNew from '../views/Admin/Payments/New.vue'
import PaymentEdit from '../views/Admin/Payments/Edit.vue'

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
  {
    path: '/employees',
    component: EmployeeIndex,
    children: [
      {path: '', component: EmployeeList, props: true, name: 'employees.index'},
      {path: 'new', component: EmployeeNew, props: true, name: 'employees.new'},
      {path: 'edit/:id', component: EmployeeEdit, props: true, name: 'employees.edit'},
    ]
  },
  {
    path: '/apartments',
    component: ApartmentIndex,
    children: [
      {path: '', component: ApartmentList, props: true, name: 'apartments.index'},
      {path: 'new', component: ApartmentNew, props: true, name: 'apartments.new'},
      {path: 'edit/:id', component: ApartmentEdit, props: true, name: 'apartments.edit'},
    ]
  },
  {
    path: '/payments',
    component: PaymentIndex,
    children: [
      {path: '', component: PaymentList, props: true, name: 'payments.index'},
      {path: 'new', component: PaymentNew, props: true, name: 'payments.new'},
      {path: 'edit/:id', component: PaymentEdit, props: true, name: 'payments.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router