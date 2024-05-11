<script setup lang="ts">
import { AuthLayout } from '@/Layouts';

defineProps<{
  canLogin?: boolean;
  canRegister?: boolean;
  laravelVersion: string;
  phpVersion: string;
}>();
</script>

<template>
  <AuthLayout> Welcome! </AuthLayout>
</template>
