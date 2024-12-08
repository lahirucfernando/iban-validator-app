<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-row>
      <v-col cols="12" class="d-flex justify-center">
        <v-card class="mx-auto">
          <v-toolbar cards dark flat>
            <v-card-title class="text-h6 font-weight-regular">
              Sign up
            </v-card-title>
            <v-spacer />
          </v-toolbar>

          <v-form ref="formRef" class="pa-4 pt-6" @submit.prevent="signUp">
            <v-text-field
              v-model="name"
              :rules="rules.name"
              color="deep-purple"
              label="Name"
              variant="filled"
              required
            />
            <v-text-field
              v-model="email"
              :rules="rules.email"
              color="deep-purple"
              label="Email address"
              type="email"
              variant="filled"
              :error-messages="emailErrors"
            />
            <v-text-field
              v-model="password"
              :rules="rules.password"
              color="deep-purple"
              counter="8"
              label="Password"
              style="min-height: 96px"
              type="password"
              variant="filled"
            />
            <v-text-field
              v-model="passwordConfirmation"
              :rules="rules.password_confirmation"
              color="deep-purple"
              counter="8"
              label="Confirm Password"
              style="min-height: 96px"
              type="password"
              variant="filled"
            />
            <v-divider />
            <v-card-actions>
              <v-btn variant="text" @click="reset"> Clear </v-btn>
              <v-spacer />
              <v-btn color="success" type="submit"> Submit </v-btn>
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
import { validateEmail, required, minLength, validatePasswordConfirmation } from "@/utils/validation";

export default {
  setup() {
    const name = ref("");
    const email = ref("");
    const password = ref("");
    const passwordConfirmation = ref("");
    const emailErrors = ref("");
    const formRef = ref(null);
    const loading = ref(false);
    const router = useRouter();
    const authStore = useAuthStore();

    const rules = {
      name: [(v) => required(v, "name")],
      email: [(v) => validateEmail(v)],
      password: [(v) => required(v, "Password"), (v) => minLength(v, 8)],
      password_confirmation: [
        (v) => required(v, "Confirmation password"),
        (v) => validatePasswordConfirmation(v, password.value),
      ],
    };

    const getAllFormValues = () => {
      return {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      };
    };

    const signUp = async () => {
      const isValid = await formRef.value.validate();
      if (isValid?.valid) {
        try {
          const formData = getAllFormValues();
          await authStore.signUp(formData);
          router.push("/dashboard");
        } catch (error) {
          console.error("SignUp failed:", error);
          if (error.response && error.response?.data?.errors?.email) {
            emailErrors.value = error.response.data.errors.email;
          }
        }
      } else {
        console.log("Form is invalid");
      }
    };

    const reset = () => formRef.value.reset();

    return {
      rules,
      name,
      email,
      reset,
      password,
      formRef,
      signUp,
      loading,
      emailErrors,
      passwordConfirmation,
    };
  },
};
</script>

<style lang="scss" scoped>
.v-card {
  width: 400px;
  margin: auto;
}
</style>
