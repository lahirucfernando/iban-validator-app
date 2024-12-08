import api from "@/services/axios";
import { reactive } from "vue";

export const useGetIbanList = () => {
  const response = reactive({
    loading: true,
    errored: false,
    data: [],
    meta: null,
    message: "",
  });
  const process = (page) => {
    response.loading = true;
    const ENDPOINT = `/iban-number-list`;
    api
      .get(ENDPOINT, { params: { page } })
      .then((apiResponse) => {
        response.loading = false;
        response.errored = false;
        response.data = apiResponse?.data?.data ?? [];
        response.meta = apiResponse?.data?.meta ?? null;
      })
      .catch((error) => {
        console.error("Iban list request failed:", error);
        response.errored = true;
        response.loading = false;
        response.message = error.response?.data?.error?.message;
        response.data = [];
      })
      .finally(() => (response.loading = false));
  };
  return {
    response,
    process,
  };
};
