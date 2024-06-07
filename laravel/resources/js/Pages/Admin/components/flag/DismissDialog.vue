<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
  flagId: number | string;
}>();

const dialog = ref(false);
const loading = ref(false);
const toast = useToasts();

function dismiss() {
  router.delete(route('admin.flags.dismiss', { id: props.flagId }), {
    onStart: () => (loading.value = true),
    onSuccess: () => toast.success('Successfully dismissed report'),
    onFinish: () => (loading.value = false),
    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}
</script>

<template>
  <v-dialog max-width="400" v-model="dialog" :persistent="loading">
    <template #activator="{ props }">
      <v-btn
        prepend-icon="mdi-check"
        variant="flat"
        v-bind="props"
        color="success"
      >
        Dismiss report
      </v-btn>
    </template>
    <v-card title="Dismiss Report">
      <v-card-text>
        <p>Are you sure you want to dismiss this report?</p>
      </v-card-text>
      <v-card-actions>
        <v-btn @click="dialog = false" :disabled="loading">Cancel</v-btn>
        <primary-btn @click="dismiss" :loading="loading">Yes</primary-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
