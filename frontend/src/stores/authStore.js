import { defineStore } from "pinia";
import api from "@/services/axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("authToken") || null,
    user: JSON.parse(localStorage.getItem("authUser")) || null,
  }),
  actions: {
    setTokenAndUser(token, user) {
      this.token = token;
      this.user = user;
      localStorage.setItem("authToken", token);
      localStorage.setItem("authUser", JSON.stringify(user));
    },

    async login(email, password) {
      try {
        const response = await api.post("/login", { email, password });
        const { data } = response.data;
        const { token, user } = data;
        this.setTokenAndUser(token, user);
        return response.data;
      } catch (error) {
        console.error("Login error:", error);
        throw error;
      }
    },

    async signUp(payload) {
      try {
        const response = await api.post("/user-register", payload);
        const { data } = response.data;
        const { token, user } = data;
        this.setTokenAndUser(token, user);
        return response.data;
      } catch (error) {
        console.error("signUp error:", error);
        throw error;
      }
    },

    loadTokenAndUser() {
      const token = localStorage.getItem("authToken");
      if (token) {
        this.token = token;
      }
      const user = localStorage.getItem("authUser");
      if (user) {
        this.user = JSON.parse(user);
      }
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("authToken");
      localStorage.removeItem("authUser");
    },

    async saveIbanNumber(payload) {
      try {
        const response = await api.post("/save-iban", payload);
        return response.data;
      } catch (error) {
        console.error("saveIbanNumber error:", error);
        throw error;
      }
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    getUser: (state) => state.user,
  },
});
