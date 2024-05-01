<script lang="ts" setup>
import { navigation } from '@/configs/navigation';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <v-list nav>
    <Link
      v-for="item in navigation.items"
      :key="item.to"
      :href="item.to"
      as="div"
    >
      <v-list-item
        link
        :prepend-icon="item.icon"
        :active="$page.url.startsWith(item.to)"
        :title="item.title"
      />
    </Link>
  </v-list>
</template>
