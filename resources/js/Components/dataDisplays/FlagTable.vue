<script lang="ts" setup>
import { Flag, Image } from '@/types';
import { formatDate } from '@/utils';
import { LinkBtn } from '../buttons';
import { useDisplay } from 'vuetify';
import DismissDialog from '@/Pages/Admin/components/flag/DismissDialog.vue';
import DeleteDialog from '@/Pages/Admin/components/flag/DeleteDialog.vue';

defineProps<{
  flags: Flag[];
  itemsLength: number;
}>();

defineEmits<{
  (e: 'search', value?: string): void;
  (e: 'delete', value?: number[]): void;
}>();

const selected = defineModel<number[]>();
const page = defineModel<number | string>('page');
const itemsPerPage = defineModel<string | number>('itemsPerPage', {
  default: 25,
});

function getItemUrl(image: Image) {
  if (image.optimized_images) {
    const img = image.optimized_images.find((im) => im.size === 'small');
    if (img) return img?.url;
  }
  return image.url;
}

const { smAndDown } = useDisplay();

const headers = [
  {
    title: 'Thumbnail',
    key: 'flaggable',
  },
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
    show-select
    v-model="selected"
    v-model:items-per-page="itemsPerPage"
    v-model:page="page"
    :items="flags"
    :itemsLength
    :headers
    item-value="id"
    :mobile="smAndDown"
    @update:page="$emit('search')"
    @update:items-per-page="$emit('search')"
  >
    <template #top>
      <v-toolbar>
        <v-card-actions>
          <DismissDialog
            :flag-id="selected"
            :disabled="!selected?.length"
            label="Dismiss Selected"
          />

          <DeleteDialog
            :flag-id="selected"
            :disabled="!selected?.length"
            label="Deleted Selected"
          />
        </v-card-actions>
      </v-toolbar>
    </template>
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
    <template #item.flaggable="{ item }">
      <v-img :src="getItemUrl(item.flaggable!)" />
    </template>
  </v-data-table-server>
</template>
