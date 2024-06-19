<script lang="ts" setup>
import type { Image, OptimizedImage } from '@/types';
import { copyToClipboard, formatBytes } from '@/utils';
import axios from 'axios';

const props = defineProps<{
  image: OptimizedImage | Image;
}>();

async function handleCopy() {
  const url = props.image.url;
  if (url.startsWith('http')) await copyToClipboard(url);
  else await copyToClipboard(`http://localhost:8000${url}`);
}

async function handleDownload() {
  const url = props.image.url;

  try {
    const res = await axios.get(url, { responseType: 'blob' });
    if (res) {
      const blob = await res.data;

      const blobUrl = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = blobUrl;
      a.download = `${props.image.id}-${props.image.}`;

      document.body.appendChild(a);
      a.click();

      window.URL.revokeObjectURL(blobUrl);
      document.body.removeChild(a);
    }
  } catch (error) {
    console.error('Failed to download image', error);
  }
}
</script>

<template>
  <v-list-item>
    <v-list-item-title>
      {{ typeof image.size === 'string' ? image.size : 'Original' }}
    </v-list-item-title>
    <v-list-item-subtitle>
      ({{ image.width }} x {{ image.height }})
      {{
        'file_size' in image
          ? formatBytes(image.file_size)
          : formatBytes(image.size)
      }}
    </v-list-item-subtitle>
    <v-list-item-action>
      <v-btn
        variant="flat"
        color="secondary"
        class="mr-2"
        size="small"
        prepend-icon="mdi-content-copy"
        @click="handleCopy"
      >
        Copy URL
      </v-btn>
      <primary-btn
        size="small"
        prepend-icon="mdi-download"
        @click="handleDownload"
      >
        Download
      </primary-btn>
    </v-list-item-action>
  </v-list-item>
</template>
