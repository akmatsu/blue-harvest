<script lang="ts" setup>
import { Image } from '@/types';
import { formatDate } from '@/utils';
import { useDisplay } from 'vuetify';
import { LinkBtn } from '../buttons';
import { useTableProps } from '@/composables/useTableProps';

defineProps<{
  images: Image[];
  itemsLength: number;
  to: string;
}>();
defineEmits<{
  (e: 'search', value?: string): void;
  (e: 'deleteImage', value?: number | number[]): void;
}>();

const { smAndDown } = useDisplay();
const { getItemUrl, itemsPerPage, page, selected } = useTableProps();

const headers = [
  {
    title: 'Thumbnail',
    key: 'url',
  },
  {
    title: 'ID',
    key: 'id',
  },
  {
    title: 'TYPE',
    key: 'mime_type',
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
    title: 'Restricted',
    key: 'is_restricted',
  },
  {
    title: 'Restrictions',
    key: 'restrictions',
  },
  {
    title: 'Status',
    key: 'status',
  },
  {
    title: 'Tags',
    key: 'tags',
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
    v-model:page="page"
    :items="images"
    :items-length="itemsLength"
    :items-per-page-options="[10, 25, 50, 100]"
    :headers="headers"
    item-value="id"
    show-select
    :mobile="smAndDown"
    @update:page="$emit('search')"
    @update:items-per-page="$emit('search')"
  >
    <template #top>
      <v-toolbar>
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
    <template #item.is_restricted="{ item }">
      {{ !!item.is_restricted }}
    </template>
    <template #item.actions="{ item }">
      <LinkBtn :link="to" :params="{ id: item.id }" color="primary">
        View Image
      </LinkBtn>
    </template>
    <template #item.url="{ item }">
      <v-img :src="getItemUrl(item)"></v-img>
    </template>
    <template #item.restrictions="{ item }">
      <v-chip-group column>
        <v-chip v-for="r in item.restrictions" density="compact">
          {{ r.name }}
        </v-chip>
      </v-chip-group>
    </template>
    <template #item.tags="{ item }">
      <v-chip-group column>
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
