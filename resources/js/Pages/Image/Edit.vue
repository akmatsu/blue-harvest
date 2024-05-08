<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Image } from '@/types';
import { useAspectRatio } from '@/composables/useAspectRatio';
import CoreLayout from '@/Layouts/CoreLayout.vue';

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
    title: 'WEBP',
    value: 'webp',
  },
  {
    title: 'PNG',
    value: 'png',
  },
  {
    title: 'JPG',
    value: 'jpg',
  },
];

const cropOptions = [
  {
    title: 'None',
    value: 'none',
  },
  {
    title: 'Smart',
    value: 'smart',
  },
  {
    title: 'Center',
    value: 'center',
  },
  {
    title: 'Top',
    value: 'top',
  },
  {
    title: 'Top Left',
    value: 'top-left',
  },

  {
    title: 'Top Right',
    value: 'top-right',
  },
  {
    title: 'Bottom',
    value: 'bottom',
  },
  {
    title: 'Bottom Right',
    value: 'bottom-right',
  },
  {
    title: 'Bottom Left',
    value: 'bottom-left',
  },
  {
    title: 'Left',
    value: 'left',
  },
  {
    title: 'Right',
    value: 'right',
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
      title: t,
      value: i,
    });
  }
  return options;
});

const lockIcon = computed(() =>
  isAspectRatioLocked.value ? 'mdi-lock' : 'mdi-lock-open-variant',
);
</script>

<template>
  <Head title="Dashboard" />

  <CoreLayout fluid>
    <VNavigationDrawer location="right" width="600">
      <v-list nav>
        <v-list-item title="Image Properties"></v-list-item>
      </v-list>
      <v-form class="pa-2">
        <div class="d-flex" style="gap: 1rem">
          <v-text-field
            v-model="width"
            type="number"
            min="1"
            max="3840"
            @input="updateHeight"
          />
          <v-text-field
            v-model="height"
            type="number"
            min="1"
            max="3840"
            @input="updateWidth"
          />
          <v-btn
            :icon="lockIcon"
            size="small"
            @click="isAspectRatioLocked = !isAspectRatioLocked"
          />
        </div>
        <v-autocomplete
          v-model="fields.crop"
          :items="cropOptions"
          name="Crop"
        />
        <v-autocomplete
          v-model="fields.extension"
          :items="fileExtOptions"
          name="File Extension"
        />
        <v-select
          v-model="fields.quality"
          :items="qualityOptions"
          name="Quality"
        />
        <v-btn>View Original Image</v-btn>
        <v-btn>Download</v-btn>
        <v-btn>Copy URL</v-btn>
      </v-form>
    </VNavigationDrawer>
    <v-container class="d-flex justify-center align-center">
      <div style="max-width: 100%">
        <v-img :src="currentUrl" height="auto" width="auto" />
      </div>
    </v-container>
  </CoreLayout>
</template>
