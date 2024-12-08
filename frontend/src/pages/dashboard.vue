<template>
  <v-layout class="rounded rounded-md">
    <v-app-bar>
      <v-row no-gutters>
        <v-col>
          <v-btn text to="/dashboard">IBAN Number</v-btn>
        </v-col>
        <v-col>
          <v-btn text to="/iban-list">IBNA Number List</v-btn>
        </v-col>
      </v-row>

      <v-spacer></v-spacer>

      <div class="mr-3">
        <v-text class="text-h6 text-capitalize">{{ user?.name }}</v-text>
      </div>
      <v-btn
        color="success"
        size="small"
        type="button"
        variant="elevated"
        @click="logout"
      >
        Logout
      </v-btn>
    </v-app-bar>

    <v-main class="d-flex align-center justify-center">
      <v-container>
        <router-view />
      </v-container>
    </v-main>
  </v-layout>
</template>

<script>
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/authStore";

export default {
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    const { user } = storeToRefs(authStore);

    const logout = () => {
      try {
        authStore.logout();
        router.push("/login");
      } catch (error) {
        console.error("Login failed:", error);
      }
    };

    return {
      user,
      logout,
    };
  },
};
</script>
