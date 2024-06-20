<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import type { Image, OptimizedImage } from '@/types';
import { copyToClipboard, formatBytes } from '@/utils';
import axios from 'axios';

const props = defineProps<{
  image: OptimizedImage | Image;
  attribution: Image['attribution'];
}>();

const toast = useToasts();

async function handleCopy() {
  const url = props.image.url;
  if (url.startsWith('http')) await copyToClipboard(url);
  else await copyToClipboard(`http://localhost:8000${url}`);
}

async function handleDownload() {
  try {
    const path = Object.hasOwn(props.image, 'image_id')
      ? 'images.download.optimized'
      : 'images.download';

    const res = await axios.get(route(path, { id: props.image.id }), {
      responseType: 'blob',
    });

    const url = window.URL.createObjectURL(res.data);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute(
      'download',
      `${props.attribution}-${props.image.id}-blue-harvest${/\.\w+$/.exec(props.image.url)?.[0]}`,
    );
    link.setAttribute('target', '_self');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    toast.success('Downloading image.');
  } catch (err) {
    toast.error('Failed to download file.');
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
