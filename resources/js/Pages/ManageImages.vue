<script lang="ts" setup>
import { CCard, Header2, ImageCard, PageContainer } from '@/Components';
import { Head } from '@inertiajs/vue3';
import { AuthenticatedLayout } from '@/Layouts';
import { Image } from '@/types';

defineProps<{ images: Image[] }>();
</script>

<template>
  <Head title="Manage Images" />
  <AuthenticatedLayout>
    <template #header>
      <Header2>Browse Images</Header2>
    </template>
    <PageContainer>
      <CCard title="Images" class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
          <ImageCard v-for="image in images" :key="image.id" :image="image" />
        </div>
      </CCard>
    </PageContainer>
  </AuthenticatedLayout>
</template>
