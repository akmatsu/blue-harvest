<script lang="ts" setup>
import { LinkBtn } from '@/Components';
import { User } from '@/types';

defineProps<{
  users: User[];
}>();
</script>

<template>
  <v-list>
    <v-list-item v-for="user in users">
      <v-list-item-title>{{ user.name }}</v-list-item-title>
      <LinkBtn
        color="primary"
        link="admin.users.view"
        :params="{ id: user.id }"
      >
        View User
      </LinkBtn>
    </v-list-item>
  </v-list>
</template>
