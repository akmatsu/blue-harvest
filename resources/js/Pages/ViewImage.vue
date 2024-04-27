<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
  CCard,
  InputLabel,
  PrimaryButton,
  SecondaryButton,
  SelectField,
  TextField,
} from '@/Components';
import { Image } from '@/types';
import {
  reactive,
  computed,
  ref,
  onBeforeMount,
  watchEffect,
  watch,
} from 'vue';
import { useAspectRatio } from '@/composables/useAspectRatio';

const props = defineProps<{
  image: Image;
}>();

const { width, height, updateWidth, updateHeight, isAspectRatioLocked } =
  useAspectRatio(props.image.width, props.image.height);

const fields = reactive({
  crop: 'none',
  extension: props.image.mime_type?.replace('image/', '') || 'webp',
  quality: 100,
});

const currentUrl = ref<string>(props.image.url);

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
    text: 'JPG',
    value: 'jpg',
  },
];

const cropOptions = [
  {
    text: 'None',
    value: 'none',
  },
  {
    text: 'Smart',
    value: 'smart',
  },
];

let timeout: number;
watch([height, fields, width], () => {
  if (timeout) clearTimeout(timeout);

  timeout = setTimeout(() => {
    let url = `http://localhost:8000/api/v1/blue-harvest?id=${props.image.id}&crop=${fields.crop}&extension=${fields.extension}&quality=${fields.quality}`;
    if (height.value) url += `&height=${height.value}`;
    if (width.value) url += `&width=${width.value}`;
    currentUrl.value = url;
  }, 500);
});

onBeforeMount(() => {
  clearTimeout(timeout);
});

const qualityOptions = computed(() => {
  const options = [];
  for (let i = 1; i <= 100; i++) {
    const t = i.toString();
    options.push({
      text: t,
      value: i,
    });
  }
  return options;
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="flex p-4 gap-4">
      <CCard
        title="Image Properties"
        class="w-full sticky top-4 h-fit max-w-xl overflow-visible"
      >
        <form>
          <div class="flex gap-4 flex-wrap items-center">
            <div>
              <InputLabel>Width</InputLabel>
              <TextField
                v-model="width"
                type="number"
                min="1"
                max="3840"
                @input="updateHeight"
              />
            </div>
            <div>
              <InputLabel>Height</InputLabel>
              <TextField
                v-model="height"
                type="number"
                min="1"
                max="3840"
                @input="updateWidth"
              />
            </div>

            <SecondaryButton
              @click="isAspectRatioLocked = !isAspectRatioLocked"
            >
              <svg
                v-if="isAspectRatioLocked"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                />
              </svg>

              <svg
                v-if="!isAspectRatioLocked"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                />
              </svg>
            </SecondaryButton>
          </div>
          <SelectField v-model="fields.crop" :items="cropOptions" name="Crop" />
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
        </form>
      </CCard>
      <CCard class="w-full flex flex-col" title="Preview">
        <div class="h-full flex flex-col justify-between gap-4">
          <div class="mx-auto">
            <img :src="currentUrl" class="max-w-full" />
          </div>

          <div
            class="flex flex-wrap flex-col sm:flex-row justify-end gap-2 items-center"
          >
            <SecondaryButton>View Original Image</SecondaryButton>
            <SecondaryButton>Download</SecondaryButton>
            <PrimaryButton>Copy URL</PrimaryButton>
          </div>
        </div>
      </CCard>
    </div>
  </AuthenticatedLayout>
</template>
