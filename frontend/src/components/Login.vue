<template>
  <v-container>
    <v-row
      fluid
      fill-height
      class="login-container"
      align="center"
      justify="center"
    >
      <v-col cols="12" sm="8" md="5">
        <v-card>
          <v-card-title class="text-h5">
            Welcome {{ loginError }}
          </v-card-title>
          <v-card-subtitle class="pb-1">
            Enter your account details to login.
          </v-card-subtitle>
          <v-form ref="formRef" @submit.prevent="login">
            <v-card-text class="pb-0">
              <v-text-field
                v-model="email"
                label="Email"
                type="email"
                :rules="rules.email"
                required
              />
              <v-text-field
                v-model="password"
                label="Password"
                type="password"
                :rules="rules.password"
                required
              />
              <v-btn
                color="success"
                size="large"
                type="submit"
                variant="elevated"
                block
              >
                Login
              </v-btn>
            </v-card-text>

            <!-- Conditionally show error message -->
            <div v-if="loginError" class="custom-error">
              {{ loginError }}
            </div>

            <v-card-actions class="pb-3">
              <v-btn size="x-small" color="primary" @click="goToSignUp">
                Sign Up
              </v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";
import { validateEmail, required } from "@/utils/validation";

export default {
  setup() {
    const email = ref("");
    const password = ref("");
    const formRef = ref(null);
    const loginError = ref("");
    const router = useRouter();
    const authStore = useAuthStore();

    const rules = {
      email: [(v) => validateEmail(v)],
      password: [(v) => required(v, 'Password')]
    };

    const login = async () => {
      const isValid = await formRef.value.validate();
      if (isValid?.valid) {
        try {
          await authStore.login(email.value, password.value);
          router.push("/dashboard");
        } catch (error) {
          console.error("Login failed:", error);
          if (error.response && error.response.data.message) {
            loginError.value = error.response.data.message;
          }
        }
      } else {
        console.log("Form is invalid");
      }
    };

    const goToSignUp = () => router.push("/signup");

    return {
      email,
      rules,
      login,
      formRef,
      password,
      loginError,
      goToSignUp,
    };
  },
};
</script>

<style lang="scss" scoped>
@use "@/styles/variables.scss" as *;

.login-container {
  background-color: $white;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.v-card {
  max-width: 400px;
  margin: auto;
}

.custom-error {
  font-size: 14px;
  margin-top: 10px;
  margin-left: 16px;
  color: $red;
}
</style>
