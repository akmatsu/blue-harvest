<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { CCard, Header2, SelectField, TextField } from '@/Components';
import { Image } from '@/types';
import { reactive, computed } from 'vue';

const props = defineProps<{
  image: Image;
}>();

const fields = reactive({
  height: 0,
  width: 0,
  crop: 'none',
  extension: 'webp',
  quality: '75',
  filter: undefined,
});

const url = computed(() => {
  let url = `http://localhost:8000/api/v1/blue-harvest?id=${props.image.id}&crop=${fields.crop}&extension=${fields.extension}&quality=${fields.quality}`;

  // Optionally adding fields that don't have default values
  if (fields.height) url += `&height=${fields.height}`;
  if (fields.width) url += `&width=${fields.width}`;
  if (fields.filter) url += `&filter=${fields.filter}`;

  return url;
});

const selectOptions = [
  {
    text: 'muffins',
    value: 'muffins',
  },
];

const fileExtOptions = [
  {
    text: 'WEBP',
    value: 'webp',
  },
  {
    text: 'PNG',
    value: 'png',
  },
  {
    text: 'JPEG',
    value: 'JPEG',
  },
  {
    text: 'JPG',
    value: 'jpg',
  },
];

const qualityOptions = computed(() => {
  const options = [];
  for (let i = 1; i < 100; i++) {
    const t = i.toString();
    options.push({
      text: t,
      value: t,
    });
  }
  return options;
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <Header2>Dashboard</Header2>
    </template>

    <div class="flex p-4 gap-4">
      <CCard title="Image" class="w-full sticky top-4 h-fit max-w-xl">
        <form @submit.prevent>
          <TextField v-model="fields.width" name="Width in px" type="number" />
          <TextField
            v-model="fields.height"
            name="Height in px"
            type="number"
          />
          <TextField v-model="fields.crop" name="Crop" />
          <SelectField
            v-model="fields.extension"
            :items="fileExtOptions"
            name="File Extension"
          />
          <SelectField
            v-model="fields.quality"
            :items="qualityOptions"
            name="Quality"
          />
          <SelectField
            v-model="fields.filter"
            :items="selectOptions"
            name="Filter"
          />
        </form>
      </CCard>
      <CCard class="w-full flex flex-col items-center">
        <img :src="image.url" />
        <img :src="url" />
      </CCard>
    </div>
  </AuthenticatedLayout>
</template>
