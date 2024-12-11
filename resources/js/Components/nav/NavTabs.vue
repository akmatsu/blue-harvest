<script lang="ts" setup>
import { LinkBtn, UserMenu } from '@/Components';
import { NavItem } from '@/configs/navigation';

import { usePage } from '@inertiajs/vue3';

defineProps<{
  items?: NavItem[];
}>();
const page = usePage();
const isAuth = computed(() => !!page.props.auth.user);
</script>

<template>
  <div class="d-flex align-center" style="gap: 8px">
    <div
      v-for="item in items"
      :key="item.to"
      :class="[{ 'd-none': item.requireAuth && !isAuth }]"
    >
      <v-btn
        v-if="!item.requireAuth || isAuth"
        :href="route(item.to)"
        :active="route(item.to) === page.props.ziggy.location"
        :prepend-icon="item.icon"
        @click.prevent.stop="$inertia.get(route(item.to))"
      >
        {{ item.title }}
      </v-btn>
    </div>

    <UserMenu v-if="isAuth" />

    <VBtn v-else color="primary" href="/azure/redirect" variant="flat">
      Log In
    </VBtn>
  </div>
</template>
