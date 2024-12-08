<template>
  <v-container class="fill-height d-flex align-center justify-center">
    <v-row>
      <v-col cols="12" class="d-flex justify-center">
        <v-card class="mx-auto">
          <v-toolbar dark flat>
            <v-card-title class="text-h6 font-weight-regular">
              Enter IBAN Number
            </v-card-title>
            <v-spacer />
          </v-toolbar>

          <v-form ref="formRef" class="pa-4 pt-6" @submit.prevent="saveIban">
            <v-text-field
              v-model="ibanNumber"
              :rules="rules.iban"
              color="deep-purple"
              label="IBAN Number"
              variant="filled"
              :error-messages="errors"
              required
            />
            <v-card-actions>
              <v-btn
                color="primary"
                size="small"
                variant="elevated"
                type="submit"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-form>

          <v-snackbar v-model="snackbar" color="green">
            IBAN Number Save Successful
            <template v-slot:actions>
              <v-btn color="pink" variant="text" @click="snackbar = false">
                Close
              </v-btn>
            </template>
          </v-snackbar>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "@/stores/authStore";
import { required } from "@/utils/validation";

export default {
  setup() {
    const ibanNumber = ref("");
    const errors = ref("");
    const formRef = ref(null);
    const snackbar = ref(false)
    const authStore = useAuthStore();

    const rules = {
      iban: [(v) => required(v, "IBAN number")],
    };

    const saveIban = async () => {
      snackbar.value = false
      const isValid = await formRef.value.validate();
      if (isValid?.valid) {
        try {
          errors.value = null;
          await authStore.saveIbanNumber({ iban: ibanNumber.value });
          snackbar.value = true
        } catch (error) {
          console.error("saveIban failed:", error);
          if (error.response && error.response?.data?.errors?.iban) {
            errors.value = error.response.data.errors.iban;
          }
        }
      } else {
        console.log("Form is invalid");
      }
    };

    return {
      rules,
      snackbar,
      ibanNumber,
      formRef,
      saveIban,
      errors,
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
