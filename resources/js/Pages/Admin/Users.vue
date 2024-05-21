<script lang="ts" setup>
import { UserTable } from '@/Components';
import { usePagination } from '@/composables/usePagination';
import { AdminLayout } from '@/Layouts';
import { Paginated, User } from '@/types';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  users: Paginated<User>;
}>();

const search = ref<string>();
const selected = ref<number[]>([]);
const { itemsPerPage, page } = usePagination(props.users);

function handleSearch(query = search.value) {
  router.get(
    route('admin.users'),
    {
      query,
      count: itemsPerPage.value,
      ...(!query && { page: page.value }),
    },
    {
      only: ['users'],
    },
  );
}
</script>

<template>
  <AdminLayout fluid searchable @search-submit="handleSearch">
    <v-card title="Users">
      <UserTable
        v-model="selected"
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :users="users.data"
        :items-length="users.total"
        @search="handleSearch"
      />
    </v-card>
  </AdminLayout>
</template>
