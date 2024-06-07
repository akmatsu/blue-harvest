<script lang="ts" setup>
import { Flag } from '@/types';
import { formatDate } from '@/utils';
import { LinkBtn } from '../buttons';

defineProps<{
  flags: Flag[];
  itemsLength: number;
}>();

const emits = defineEmits<{
  (e: 'search', value?: string): void;
  (e: 'delete', value?: number[]): void;
}>();

const selected = defineModel<number[]>();

const itemsPerPage = defineModel<string | number>('itemsPerPage', {
  default: 25,
});

const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Type',
    key: 'flaggable_type',
  },
  {
    title: 'Reason',
    key: 'reason',
  },
  {
    title: 'User',
    key: 'user_id',
  },
  {
    title: 'Date reported',
    key: 'created_at',
  },
  {
    title: 'Actions',
    key: 'actions',
  },
];
</script>

<template>
  <v-data-table-server
    v-model="selected"
    v-model:items-per-page="itemsPerPage"
    item-value="id"
    :items="flags"
    :itemsLength
    :headers
    @update:page="$emit('search')"
    @update:items-per-page="$emit('search')"
  >
    <template #item.created_at="{ item }">
      {{ formatDate(item.created_at) }}
    </template>
    <template #item.user_id="{ item }">
      {{ item.user_id ? item.user_id : 'Automated' }}
    </template>
    <template #item.actions="{ item }">
      <LinkBtn
        color="primary"
        link="admin.flags.show"
        :params="{ id: item.id }"
      >
        View
      </LinkBtn>
    </template>
  </v-data-table-server>
</template>
