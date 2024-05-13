<script lang="ts" setup>
import { UserMenu } from '@/Components';
import { navigation } from '@/configs/navigation';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const isAuth = computed(() => !!page.props.auth.user);
</script>

<template>
  <div class="d-flex align-center" style="gap: 8px">
    <div
      v-for="item in navigation.items"
      :key="item.to"
      :class="[{ 'd-none': item.requireAuth && !isAuth }]"
    >
      <v-btn
        v-if="!item.requireAuth || isAuth"
        :href="item.to"
        :active="page.url === item.to"
        :prepend-icon="item.icon"
        @click.prevent.stop="$inertia.get(item.to)"
      >
        {{ item.title }}
      </v-btn>
    </div>
    <UserMenu v-if="isAuth" />
    <primary-btn
      v-else
      prepend-icon="mdi-account-plus"
      href="/register"
      @click.prevent.stop="$inertia.get('/register')"
    >
      Register
    </primary-btn>
  </div>
</template>
