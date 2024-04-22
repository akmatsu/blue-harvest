<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getOptimizedImage } from '@/utils/apiAbstractions/userClient';
import { Head } from '@inertiajs/vue3';

async function handleClick() {
  const image = await getOptimizedImage();
  console.log(image);
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
        >
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <button @click="handleClick()">Click me!</button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
