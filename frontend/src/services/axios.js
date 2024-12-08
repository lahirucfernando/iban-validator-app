import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';

// Create an Axios instance
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL, 
});

// Add a request interceptor
api.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
      config.headers['Authorization'] = `Bearer ${authStore.token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;
