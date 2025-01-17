<script lang="ts" setup>
import { LinkBtn } from '@/Components';
import { CoreLayout } from '@/Layouts';
import { Image, Tag } from '@/types';
import { UploadResultCard } from './Upload/components';

const props = defineProps<{
  images: Image[];
  tags: Tag[];
}>();

const selectedIds = ref<Number[]>([]);
const bulkTitle = ref('');
const bulkTags = ref<string[]>([]);

const isAllSelected = computed(() => {
  return selectedIds.value.length === props.images.length;
});

const buttonText = computed(() => {
  if (isAllSelected.value) {
    return 'Deselect All';
  }

  return 'Select All';
});

function updateSelection(id: number, selected: boolean) {
  if (selected && !selectedIds.value.includes(id)) {
    selectedIds.value.push(id);
  } else if (!selected) {
    selectedIds.value = selectedIds.value.filter((i) => i !== id);
  }
}

function selectAllToggle() {
  if (isAllSelected.value) {
    console.log('deselect all');
    selectedIds.value = [];
  } else {
    console.log('Select all');
    selectedIds.value = props.images.map((image) => image.id);
  }
}

function handleBulkUpdate() {
  console.log(selectedIds.value, bulkTitle.value, bulkTags.value);
}
</script>

<template>
  <CoreLayout title="Upload Results">
    <v-alert icon="mdi-information" color="success">
      You're images were successfully uploaded and are being processed. You will
      receive a notification when they are finished processing.
      <template #append>
        <div class="d-flex flex-column" style="gap: 4px">
          <LinkBtn link="upload.index"> Upload more images </LinkBtn>
          <LinkBtn link="index" color="primary">Back to app</LinkBtn>
        </div>
      </template>
    </v-alert>
    <div class="d-flex justify-end">
      <v-btn color="secondary" @click="selectAllToggle">{{ buttonText }}</v-btn>
      <v-dialog width="600" max-width="100%">
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            color="primary"
            :disabled="selectedIds.length === 0"
          >
            Edit Selected
          </v-btn>
        </template>
        <v-card>
          <v-card-title>Edit Selected</v-card-title>
          <v-card-text>
            <v-form>
              <v-text-field v-model="bulkTitle" label="Title" outlined dense />
              <v-autocomplete
                v-model="bulkTags"
                label="Tags"
                multiple
                chips
                item-value="name"
                item-title="name"
                :items="tags"
              />
              <v-btn @click="handleBulkUpdate">Update Selected</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
    <v-list>
      <div v-for="(image, index) in images" :key="image.id">
        <UploadResultCard
          :image="image"
          :tags="tags"
          :selection="selectedIds"
          @update:selected="updateSelection(image.id, $event)"
        />
        <v-divider v-if="index !== images.length - 1"></v-divider>
      </div>
    </v-list>
  </CoreLayout>
</template>
