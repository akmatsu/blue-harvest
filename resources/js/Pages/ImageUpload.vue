<script lang="ts" setup>
import { DragAndDropInput } from '@/Components';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { Head, useForm } from '@inertiajs/vue3';

const isFormValid = ref(false);
const toast = useToasts();
const dialog = ref(false);
const progress = ref(0);
const isDragging = ref(false);

const form = useForm<{
  files?: File[];
}>({
  files: undefined,
});

async function handleSubmit() {
  if (isFormValid.value)
    form.post('/images', {
      onStart: () => (dialog.value = true),
      onSuccess: () => toast.success('Successfully uploaded images!'),
      onError: (err) => {
        for (const key in err) toast.error(err[key]);
      },
      onProgress: (p) => {
        progress.value = p?.percentage || progress.value;
      },
      onFinish: () => {
        dialog.value = false;
        progress.value = 0;
      },
    });
}

const processText = computed(() =>
  progress.value < 100 ? 'Uploading Images...' : 'Processing Images...',
);

function onDragOver() {
  isDragging.value = true;
}

function onDragLeave() {
  isDragging.value = false;
}

function onDrop(event: DragEvent) {
  isDragging.value = false;
  const files = event.dataTransfer?.files;
  if (files) {
    form.files ? form.files.push(...files) : (form.files = [...files]);
  }
}
</script>

<template>
  <Head title="Image Upload" />
  <CoreLayout
    @dragover.prevent="onDragOver"
    @dragleave="onDragLeave"
    @drop.prevent="onDrop"
  >
    <v-fade-transition>
      <div
        :class="['drag-drop-area', { dragging: isDragging }]"
        v-if="isDragging"
      >
        <v-icon icon="mdi-upload" size="50" color="white"></v-icon>
      </div>
    </v-fade-transition>
    <v-card title="Image Upload Form" class="w-full">
      <v-card-text>
        <v-form v-model="isFormValid" @submit.prevent="handleSubmit">
          <DragAndDropInput v-model="form.files" :rules="required" />

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              variant="flat"
              color="primary"
              type="submit"
              :loading="form.processing"
            >
              Upload
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card-text>
    </v-card>
    <v-dialog v-model="dialog" max-width="400px" :persistent="dialog">
      <v-card
        :title="processText"
        subtitle="This can take a while. Please do not leave this page."
      >
        <v-card-text>
          <v-progress-linear
            :model-value="progress"
            color="primary"
            :indeterminate="progress === 100"
            height="30"
            striped
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </CoreLayout>
</template>

<style lang="scss" scoped>
.drag-drop-area {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  transition: background-color 0.3s;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
