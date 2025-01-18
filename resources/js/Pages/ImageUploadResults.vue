<script lang="ts" setup>
import { LinkBtn } from '@/Components';
import { CoreLayout } from '@/Layouts';
import { Image } from '@/types';
import { UploadResultCard } from './Upload/components';
import { useForm } from '@inertiajs/vue3';
import { useToasts } from '@/store/toasts';
import { TagSelection } from '@/Components';

const props = defineProps<{
  images: Image[];
}>();

const opened = ref(false);
const toast = useToasts();

function closeDialog() {
  opened.value = false;
  form.reset();
}

const updatedImages = ref<Image[]>([]);

function setUpdatedImages(images: Image[]) {
  updatedImages.value = images;
}

const currentImages = computed(() => {
  return updatedImages.value.length > 0 ? updatedImages.value : props.images;
});

const form = useForm<{ ids: number[]; name: string; tags: string[] }>({
  ids: [],
  name: '',
  tags: [],
});

const isAllSelected = computed(() => {
  return form.ids.length === currentImages.value.length;
});

const buttonText = computed(() => {
  if (isAllSelected.value) {
    return 'Deselect All';
  }

  return 'Select All';
});

function updateSelection(id: number, selected: boolean) {
  if (selected && !form.ids.includes(id)) {
    form.ids.push(id);
  } else if (!selected) {
    form.ids = form.ids.filter((i) => i !== id);
  }
}

function selectAllToggle() {
  if (isAllSelected.value) {
    form.ids = [];
  } else {
    form.ids = currentImages.value.map((image) => image.id);
  }
}

function handleBulkUpdate() {
  form.patch(`/images${window.location.search}`, {
    onSuccess: (page) => {
      setUpdatedImages(page.props.images as Image[]);
      closeDialog();
    },
    onError(errors) {
      let message = 'Failed to updated images:';
      Object.keys(errors).forEach((key) => {
        message += ' ' + errors[key];
      });
      toast.error(message);
    },
  });
}
</script>

<template>
  <CoreLayout title="Upload Results">
    <v-alert icon="mdi-information" color="success" class="mb-4">
      You're images were successfully uploaded and are being processed. You will
      receive a notification when they are finished processing.
      <template #append>
        <div class="d-flex flex-column" style="gap: 4px">
          <LinkBtn link="upload.index"> Upload more images </LinkBtn>
          <LinkBtn link="index" color="primary">Back to app</LinkBtn>
        </div>
      </template>
    </v-alert>
    <div class="d-flex justify-start mb-4" :style="{ gap: '8px' }">
      <v-btn color="secondary" @click="selectAllToggle">{{ buttonText }}</v-btn>
      <v-dialog
        v-model="opened"
        width="600"
        max-width="100%"
        :persistent="form.processing"
      >
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            color="primary"
            :disabled="form.ids.length === 0"
          >
            Edit Selected
          </v-btn>
        </template>
        <v-card>
          <v-card-title>Edit Selected</v-card-title>
          <v-card-text>
            <v-form
              @submit.prevent="handleBulkUpdate"
              :disabled="form.processing"
            >
              <v-text-field v-model="form.name" label="Name" outlined dense />
              <tag-selection v-model="form.tags" />

              <div class="d-flex justify-end">
                <primary-btn type="submit" :loading="form.processing">
                  Update Selected
                </primary-btn>
              </div>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
    <v-list>
      <div v-for="(image, index) in currentImages" :key="image.id">
        <UploadResultCard
          :image="image"
          :selection="form.ids"
          @update:selected="updateSelection(image.id, $event)"
        />
        <v-divider v-if="index !== currentImages.length - 1"></v-divider>
      </div>
    </v-list>
  </CoreLayout>
</template>
