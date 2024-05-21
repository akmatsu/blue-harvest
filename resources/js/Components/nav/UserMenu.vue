<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();

onMounted(() => {
  console.log(page.props.auth.user);
});

const isAdmin = computed(
  () => !!page.props.auth.user?.roles?.find((role) => role.name === 'admin'),
);
</script>

<template>
  <v-menu>
    <template #activator="{ props }">
      <v-btn
        v-bind="props"
        prepend-icon="mdi-account"
        append-icon="mdi-chevron-down"
      >
        {{ $page.props.auth.user.name }}
      </v-btn>
    </template>

    <v-list>
      <v-list-item
        href="/profile"
        prepend-icon="mdi-account-edit"
        :active="$page.url === '/profile'"
        @click.prevent.stop="$inertia.get('/profile')"
      >
        <v-list-item-title> View Account </v-list-item-title>
      </v-list-item>

      <v-list-item
        v-if="isAdmin"
        href="/admin/images"
        prepend-icon="mdi-shield-account"
        @click.prevent.stop="$inertia.get('/admin/images')"
      >
        Admin View
      </v-list-item>

      <v-list-item
        prepend-icon="mdi-logout"
        href="/logout"
        @click.prevent.stop="$inertia.post('/logout')"
      >
        <v-list-item-title> Log out </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
