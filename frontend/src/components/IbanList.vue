<template>
  <v-app>
    <!-- Data List -->
    <v-list>
      <v-list-item-group v-if="list.data.length > 0">
        <v-list-item v-for="item in list.data" :key="item.uuid">
          <v-list-item-content>
            <v-list-item-title>{{ item.name }}</v-list-item-title>
            <v-list-item-subtitle>{{ item.email }}</v-list-item-subtitle>
            <v-list-item-subtitle>
              {{ item.iban || "No IBAN" }}
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <!-- Pagination Controls -->
        <v-pagination
          v-model="currentPage"
          :length="list?.meta?.last_page"
          :disabled="list?.meta?.last_page <= 1"
          circle
          @click="fetchIbanList(currentPage)"
        />
      </v-list-item-group>

      <v-list-item v-else>
        <v-list-item-content>No data available</v-list-item-content>
      </v-list-item>
    </v-list>
  </v-app>
</template>

<script>
import { ref, onMounted } from "vue";
import { useGetIbanList } from "@/hooks/useGetIbanList";

export default {
  setup() {
    const currentPage = ref(1);
    const { process: fetchIbanList, response: list } = useGetIbanList();
    
    onMounted(() => {
        fetchIbanList(currentPage.value);
    });

    return {
      list,
      currentPage,
      fetchIbanList
    };
  },
};
</script>
