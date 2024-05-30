<script lang="ts" setup>
import { Head, useForm } from '@inertiajs/vue3';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { required } from '@/utils';
import { useToasts } from '@/store/toasts';
import { DragAndDropInput } from '@/Components';

const isFormValid = ref(false);
const toast = useToasts();
const dialog = ref(false);
const progress = ref(0);

const form = useForm({
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
</script>

<template>
  <Head title="Image Upload" />
  <CoreLayout>
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
      <v-card :title="processText">
        <v-card-text>
          <v-progress-linear
            :model-value="progress"
            color="primary"
            :indeterminate="progress === 100"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </CoreLayout>
</template>
