<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { CCard, DragDropFileInput, Header2, PageContainer } from '@/Components';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Image Upload" />
  <AuthenticatedLayout>
    <template #header>
      <Header2> Image Upload</Header2>
    </template>
    <PageContainer>
      <CCard title="Image Upload Form" class="w-fit">
        <DragDropFileInput />
      </CCard>
    </PageContainer>
  </AuthenticatedLayout>
</template>
