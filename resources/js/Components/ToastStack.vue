<script lang="ts" setup>
import { useToasts } from '@/store/toasts';

const toast = useToasts();
</script>

<template>
  <v-snackbar-queue v-model="toast.toasts"></v-snackbar-queue>
</template>
