<script lang="ts" setup>
import { FlagTable } from '@/Components';
import { useTableViewProps } from '@/composables/useTableViewProps';
import { AdminLayout } from '@/Layouts';
import { Flag, Paginated } from '@/types';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps<{
  flags: Paginated<Flag>;
}>();

const selected = ref<number[]>([]);

const itemsPerPage = ref(props.flags.per_page);
const page = ref(props.flags.current_page);

const search = ref<string>();

function handleSearch(query = search.value) {
  return router.get(
    '/flags',
    {
      query,
      count: itemsPerPage.value,
      ...(!query && { page: page.value }),
    },
    { only: ['images'] },
  );
}
</script>

<template>
  <Head title="Flags - Admin" />
  <AdminLayout fluid v-model="search" searchable @search-submit="handleSearch">
    <v-card>
      <v-card-text>
        <FlagTable
          v-model="selected"
          v-model:page="page"
          v-model:items-per-page="itemsPerPage"
          :flags="flags.data"
          :items-length="flags.total"
          to="images.manageImage"
          @search="handleSearch"
        />
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
