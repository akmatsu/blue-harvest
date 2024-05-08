<script lang="ts" setup>
import type { Image, OptimizedImage } from '@/types';
import { formatBytes, copyToClipboard } from '@/utils';

defineProps<{
  image: OptimizedImage | Image;
}>();

async function handleCopy(url: string) {
  if (url.startsWith('http')) await copyToClipboard(url);
  else await copyToClipboard(`http://localhost:8000${url}`);
}
</script>

<template>
  <v-menu location="right">
    <template #activator="{ props }">
      <v-list-item v-bind="props" append-icon="mdi-chevron-right">
        <v-list-item-title class="text-uppercase">
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
      </v-list-item>
    </template>
    <v-list>
      <!-- <v-list-item @click="">Download</v-list-item> -->
      <v-list-item @click="handleCopy(image.url)">Copy URL</v-list-item>
    </v-list>
  </v-menu>
</template>
