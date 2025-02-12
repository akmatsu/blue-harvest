<script lang="ts" setup>
import { FlagTable } from '@/Components';
import { useTableViewProps } from '@/composables/useTableViewProps';
import { AdminLayout } from '@/Layouts';
import { Flag, Paginated } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
  flags: Paginated<Flag>;
}>();

const { search, selected, page, itemsPerPage, handleSearch, handleDelete } =
  useTableViewProps(props.flags.per_page, props.flags.current_page, '/flags');
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
          @delete-image="handleDelete"
        />
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
