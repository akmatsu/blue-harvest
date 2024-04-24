<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  CCard,
  DragDropFileInput,
  Header2,
  PageContainer,
  PrimaryButton,
} from '@/Components';
import { Head } from '@inertiajs/vue3';
import { uploadImages } from '@/utils';

async function handleSubmit() {
  const fileInput: HTMLInputElement | null =
    document.querySelector<HTMLInputElement>('#muffins');

  if (fileInput?.files) await uploadImages(fileInput.files);
}
</script>

<template>
  <Head title="Image Upload" />
  <AuthenticatedLayout>
    <template #header>
      <Header2> Image Upload</Header2>
    </template>
    <PageContainer>
      <CCard title="Image Upload Form" class="w-full">
        <form
          class="flex flex-col items-center w-full"
          @submit.prevent="handleSubmit"
        >
          <DragDropFileInput required id="muffins" />
          <PrimaryButton type="submit">Submit</PrimaryButton>
        </form>
      </CCard>
    </PageContainer>
  </AuthenticatedLayout>
</template>
