<script lang="ts" setup>
import { UserTable } from '@/Components';
import { usePagination } from '@/composables/usePagination';
import { AdminLayout } from '@/Layouts';
import { useToasts } from '@/store/toasts';
import { Paginated, User } from '@/types';
import { router, Head } from '@inertiajs/vue3';

const props = defineProps<{
  users: Paginated<User>;
}>();

const search = ref<string>();
const selected = ref<number[]>([]);
const { itemsPerPage, page } = usePagination(props.users);

const toast = useToasts();

function handleDelete(ids?: number[]) {
  if (ids) {
    router.delete(route('admin.users.delete.bulk', { ids }), {
      onSuccess: () => {
        toast.success('Successfully deleted users.');
        selected.value = [];
      },
      onError: (err) => toast.error(err.message),
    });
  } else {
    toast.warn('No users are selected.');
  }
}

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
  <Head title="Users - Admin" />
  <AdminLayout fluid searchable @search-submit="handleSearch">
    <v-card title="Users">
      <UserTable
        v-model="selected"
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :users="users.data"
        :items-length="users.total"
        @search="handleSearch"
        @delete="handleDelete"
      />
    </v-card>
  </AdminLayout>
</template>
