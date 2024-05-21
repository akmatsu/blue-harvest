<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { User } from '@/types';

defineProps<{
  user: User;
}>();
</script>

<template>
  <AdminLayout>
    <h1>{{ user.name }}</h1>
  </AdminLayout>
</template>
