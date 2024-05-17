<script lang="ts" setup>
import { CoreLinkProps } from '@/types';
import { VBtn } from 'vuetify/components';

const props = defineProps<CoreLinkProps>();

const url = computed(() => route(props.link || '', props.params));
</script>

<template>
  <v-btn
    :href="url"
    @click.prevent="
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
    <span v-if="$slots.default">
      <slot />
    </span>
  </v-btn>
</template>
