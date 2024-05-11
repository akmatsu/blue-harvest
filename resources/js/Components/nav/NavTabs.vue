<script lang="ts" setup>
import { navigation } from '@/configs/navigation';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
  <div class="d-flex" style="gap: 8px">
    <div
      v-for="item in navigation.items"
      :key="item.to"
      :class="[{ 'd-none': item.requireAuth && !page.props.auth.user }]"
    >
      <v-btn
        v-if="!item.requireAuth || !!page.props.auth.user"
        :href="item.to"
        :active="page.url === item.to"
        :prepend-icon="item.icon"
        @click.prevent.stop="$inertia.get(item.to)"
      >
        {{ item.title }}
      </v-btn>
    </div>
  </div>
</template>
