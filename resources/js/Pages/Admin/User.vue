<script lang="ts" setup>
import { User } from '@/types';

defineProps<{
  user: User;
}>();
</script>

<template>
  <h1>{{ user.name }}</h1>
</template>
