<script lang="ts" setup>
// import { CCard, Header2, ImageCard, PageContainer } from '@/Components';
import { Head } from '@inertiajs/vue3';
import { Image } from '@/types';
import { formatDate } from '@/utils';
import CoreLayout from '@/Layouts/CoreLayout.vue';

defineProps<{ images: Image[] }>();

const selected = ref([]);

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
];
</script>

<template>
  <Head title="Manage Images" />
  <CoreLayout>
    <v-data-table
      v-model="selected"
      :items="images"
      :headers="headers"
      item-value="id"
      show-select
    >
      <template #top>
        <v-toolbar flat>
          <v-toolbar-title>Your Images</v-toolbar-title>
          <v-divider inset vertical class="mx-1"></v-divider>
          <v-dialog max-width="400px">
            <template #activator="{ props }">
              <v-btn
                icon="mdi-delete"
                v-bind="props"
                color="error"
                :disabled="!selected.length"
              ></v-btn>
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
                  <v-btn variant="flat" color="error">Delete</v-btn>
                </v-card-actions>
              </v-card>
            </template>
          </v-dialog>
          <v-spacer></v-spacer>
        </v-toolbar>
      </template>
      <template #item.created_at="{ item }">
        {{ formatDate(item.created_at) }}
      </template>
      <template #item.updated_at="{ item }">
        {{ formatDate(item.updated_at) }}
      </template>
    </v-data-table>
  </CoreLayout>
</template>
