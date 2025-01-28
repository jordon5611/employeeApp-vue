import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '../components/layouts/MainLayout.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: 'country',
        name: 'country.index',
        component: () => import('../views/country/Index.vue')
      },
      {
        path: 'country/create/:id?',
        name: 'country.create',
        component: () => import('../views/country/Create.vue'),
      },
      {
        path: 'state',
        name: 'state.index',
        component: () => import('../views/state/Index.vue')
      },
      {
        path: 'state/create/:id?',
        name: 'state.create',
        component: () => import('../views/state/Create.vue')
      },
      {
        path: 'city',
        name: 'city.index',
        component: () => import('../views/city/Index.vue')
      },
      {
        path: 'city/create/:id?',
        name: 'city.create',
        component: () => import('../views/city/Create.vue')
      },
      {
        path: 'employee',
        name: 'employee.index',
        component: () => import('../views/employee/Index.vue')
      },
      {
        path: 'employee/create/:id?',
        name: 'employee.create',
        component: () => import('../views/employee/Create.vue')
      },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;