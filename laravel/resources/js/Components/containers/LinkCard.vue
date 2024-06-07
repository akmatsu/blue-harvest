<script lang="ts" setup>
import { CoreLinkProps } from '@/types';

const props = defineProps<CoreLinkProps>();

const url = computed(() => route(props.link || '', props.params));
</script>

<template>
  <v-card
    :href="url"
    @click.prevent.stop="
      $inertia.get(
        url,
        {},
        {
          preserveScroll,
          preserveState,
          only,
        },
      )
    "
  >
    <slot />
  </v-card>
</template>
