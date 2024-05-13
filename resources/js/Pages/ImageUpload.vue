<script lang="ts" setup>
import { Head, useForm } from '@inertiajs/vue3';
// import { uploadImages } from '@/utils';
import CoreLayout from '@/Layouts/CoreLayout.vue';
// import { useRequest } from '@/composables/useRequest';
import { required } from '@/utils';
import { useToasts } from '@/store/toasts';

const isFormValid = ref(false);
const toast = useToasts();

const form = useForm({
  files: null,
});

async function handleSubmit() {
  if (isFormValid.value)
    form.post('/images', {
      onSuccess: () => toast.success('Successfully uploaded images!'),
      onError: (err) => toast.error(err.message),
    });
}
</script>

<template>
  <Head title="Image Upload" />
  <CoreLayout>
    <v-card title="Image Upload Form" class="w-full">
      <v-card-text>
        <v-form v-model="isFormValid" @submit.prevent="handleSubmit">
          <v-file-input
            v-model="form.files"
            label="Images"
            multiple
            chips
            accept="image/png,image/jpeg,image/jpg,image/webp"
            :rules="[required]"
          ></v-file-input>
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
  </CoreLayout>
</template>
