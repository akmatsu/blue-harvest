<script lang="ts" setup>
import { CCard, Header2, PageContainer } from '@/Components';
import { Head, Link } from '@inertiajs/vue3';
import { AuthenticatedLayout } from '@/Layouts';
import { Image } from '@/types';

defineProps<{
  images: Image[];
}>();
</script>

<template>
  <Head title="Browse Images" />
  <AuthenticatedLayout>
    <template #header>
      <Header2>Browse Images</Header2>
    </template>
    <PageContainer>
      <CCard title="Images" class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
          <CCard v-for="image in images" :key="image.id" class="relative p-0">
            <Link
              :href="route('image-view', { id: image.id })"
              class="transition-colors absolute w-full h-full hover:bg-slate-200 opacity-30"
            ></Link>
            <img class="w-full" :src="image.url" />
            <p class="text-center pt-4 pb-2">{{ image.name }}</p>
          </CCard>
        </div>
      </CCard>
    </PageContainer>
  </AuthenticatedLayout>
</template>
