<script lang="ts" setup>
import { User } from '@/types';
import { formatDate } from '@/utils';
import { LinkBtn } from '@/Components';

defineProps<{
  users: User[];
  itemsLength: number;
}>();

const emits = defineEmits<{
  (e: 'search', value?: string): void;
  (e: 'edit', value?: number[]): void;
  (e: 'delete', value?: number | number[]): void;
}>();

const selected = defineModel<number[]>();
const page = defineModel<string | number>('page');
const itemsPerPage = defineModel<string | number>('items-per-page', {
  default: 25,
});

const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'Email',
    key: 'email',
  },
  {
    title: 'Verified',
    key: 'email_verified_at',
  },
  {
    title: 'Created on',
    key: 'created_at',
  },
  {
    title: 'Last Updated',
    key: 'updated_at',
  },
  {
    title: '',
    key: 'actions',
  },
];
</script>

<template>
  <v-data-table-server
    v-model="selected"
    v-model:items-per-page="itemsPerPage"
    v-model:page="page"
    item-value="id"
    show-select
    :items="users"
    :items-length
    :headers
    @update:page="$emit('search')"
    @update:items-per-page="$emit('search')"
  >
    <template #top>
      <v-toolbar class="px-3">
        <v-dialog max-width="400px">
          <template #activator="{ props }">
            <v-btn
              v-bind="props"
              color="error"
              prepend-icon="mdi-delete"
              :disabled="!selected?.length"
            >
              Delete
            </v-btn>
          </template>
          <template #default="{ isActive }">
            <v-card title="Delete Images">
              <v-card-text>
                <p>
                  Are you sure you want to delete the selected images? This is
                  not reversible.
                </p>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="isActive.value = false">Cancel</v-btn>
                <v-btn
                  variant="flat"
                  color="error"
                  @click="$emit('delete', selected)"
                >
                  Delete
                </v-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
      </v-toolbar>
    </template>
    <template #item.created_at="{ item }">
      {{ formatDate(item.created_at) }}
    </template>
    <template #item.updated_at="{ item }">
      {{ formatDate(item.updated_at) }}
    </template>
    <template #item.email_verified_at="{ item }">
      {{
        item.email_verified_at
          ? formatDate(item.email_verified_at)
          : 'Not verified'
      }}
    </template>
    <template #item.actions="{ item }">
      <LinkBtn
        color="primary"
        link="admin.users.view"
        :params="{ id: item.id }"
      >
        View User
      </LinkBtn>
    </template>
  </v-data-table-server>
</template>
