<script lang="ts" setup></script>

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
        prepend-icon="mdi-logout"
        href="/logout"
        @click.prevent.stop="$inertia.post('/logout')"
      >
        <v-list-item-title> Log out </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
