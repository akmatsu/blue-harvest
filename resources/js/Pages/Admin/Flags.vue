<script lang="ts" setup>
import { FlagTable } from '@/Components';
import { AdminLayout } from '@/Layouts';
import { Flag, Paginated } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
  flags: Paginated<Flag>;
}>();
</script>

<template>
  <Head title="Flags - Admin" />
  <AdminLayout fluid>
    <v-card>
      <v-card-text>
        <FlagTable :flags="flags.data" :itemsLength="flags.total" />
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
