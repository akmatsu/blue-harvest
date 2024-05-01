<script setup lang="ts">
import { GuestLayout } from '@/Layouts';

defineProps<{
  canLogin?: boolean;
  canRegister?: boolean;
  laravelVersion: string;
  phpVersion: string;
}>();
</script>

<template>
  <GuestLayout> Welcome! </GuestLayout>
</template>
