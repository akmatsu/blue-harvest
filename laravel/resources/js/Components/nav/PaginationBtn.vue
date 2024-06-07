<script lang="ts" setup>
import { LinkBtn } from '@/Components';

defineProps<{
  link?: string;
  isActive?: boolean;
  params?: {
    [key: string]: string | number | undefined;
  };
}>();
</script>

<template>
  <LinkBtn
    :link="link"
    :params="params"
    variant="text"
    size="small"
    density="compact"
  >
    <span v-if="$slots.default">
      <slot />
    </span>
  </LinkBtn>
</template>
