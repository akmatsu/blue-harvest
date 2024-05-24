<script lang="ts" setup>
import { Image } from '@/types';
import { formatDate } from '@/utils';

defineProps<{
  images: Image[];
  itemsLength: number;
}>();
defineEmits<{
  (e: 'search', value?: string): void;
  (e: 'editImages', value?: number[]): void;
  (e: 'deleteImage', value?: number | number[]): void;
}>();

const selected = defineModel<number[]>();
const page = defineModel<number | string>('page');
const itemsPerPage = defineModel<string | number>('itemsPerPage', {
  default: 25,
});

const headers = [
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'TYPE',
    key: 'mime_type',
  },
  {
    title: 'Name',
    key: 'name',
  },
  {
    title: 'Created',
    key: 'created_at',
  },
  {
    title: 'Updated',
    key: 'updated_at',
  },
  {
    title: 'Tags',
    key: 'tags',
  },
  {
    title: 'Actions',
    key: 'url',
  },
];
</script>

<template>
  <v-data-table-server
    v-model="selected"
    v-model:items-per-page="itemsPerPage"
    v-model:page="page"
    :items="images"
    :items-length="itemsLength"
    :headers="headers"
    item-value="id"
    show-select
    @update:page="$emit('search')"
    @update:items-per-page="$emit('search')"
  >
    <template #top>
      <v-toolbar>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          prepend-icon="$edit"
          @click="$emit('editImages', selected)"
        >
          Edit Images
        </v-btn>
        <v-dialog max-width="400px">
          <template #activator="{ props }">
            <v-btn
              prepend-icon="mdi-delete"
              v-bind="props"
              color="error"
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
                  @click="$emit('deleteImage', selected)"
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
    <template #item.url="{ item }">
      <v-btn color="primary" @click="$emit('editImages', [item.id])">
        View Image
      </v-btn>
    </template>
    <template #item.tags="{ item }">
      <v-chip-group>
        <v-chip
          v-for="tag in item.tags"
          density="compact"
          :href="`/images?query=${tag.name}`"
          @click.prevent.stop="$emit('search', tag.name)"
        >
          {{ tag.name }}
        </v-chip>
      </v-chip-group>
    </template>
  </v-data-table-server>
</template>
