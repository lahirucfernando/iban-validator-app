
/**
 * router/index.ts
 *
 * Automatic routes for `./src/pages/*.vue`
 */

// Composables
import { createRouter, createWebHistory } from 'vue-router/auto'
import { useAuthStore } from '@/stores/authStore';
import Dashboard from "@/pages/dashboard"; 
import Login from "@/components/login"; 
import Signup from '@/components/Signup';
import IbanForm from '@/components/IbanForm';
import IbanList from '@/components/IbanList';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      component: Login
    },
    {
      path: '/signup',
      component: Signup
    },
    {
      path: "/",
      component: Dashboard, 
      meta: { requiresAuth: true },
      children: [
        {
          path: "dashboard",
          component: IbanForm,
        },
        {
          path: "iban-list",
          component: IbanList,
        }
      ],
    },
    {
      path: "/:pathMatch(.*)*",
      redirect: "/dashboard", 
    },
  ]
})

// Workaround for https://github.com/vitejs/vite/issues/11804
router.onError((err, to) => {
  if (err?.message?.includes?.('Failed to fetch dynamically imported module')) {
    if (!localStorage.getItem('vuetify:dynamic-reload')) {
      console.log('Reloading page to fix dynamic import error')
      localStorage.setItem('vuetify:dynamic-reload', 'true')
      location.assign(to.fullPath)
    } else {
      console.error('Dynamic import error, reloading page did not fix it', err)
    }
  } else {
    console.error(err)
  }
})

router.isReady().then(() => {
  localStorage.removeItem('vuetify:dynamic-reload')
})

// Guard for Authentication
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router
