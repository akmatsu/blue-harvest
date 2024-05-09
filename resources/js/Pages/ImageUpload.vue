<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import { uploadImages } from '@/utils';
import CoreLayout from '@/Layouts/CoreLayout.vue';
import { useRequest } from '@/composables/useRequest';
import { required } from '@/utils';
import { useToasts } from '@/store/toasts';

const images = ref<File[]>([]);
const isFormValid = ref(false);
const form = ref();

const toast = useToasts();

async function handleSubmit() {
  if (isFormValid.value) await uploadImages(images.value);
}

const { exec, loading } = useRequest(handleSubmit, {
  onSuccess: () => {
    toast.success('successfully uploaded images');
  },
  onError: (err) => toast.error(err.message),
});
</script>

<template>
  <Head title="Image Upload" />
  <CoreLayout>
    <v-card title="Image Upload Form" class="w-full">
      <v-card-text>
        <v-form ref="form" v-model="isFormValid" @submit.prevent="exec(images)">
          <v-file-input
            v-model="images"
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
              :loading="loading"
            >
              Upload
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card-text>
    </v-card>
  </CoreLayout>
</template>
