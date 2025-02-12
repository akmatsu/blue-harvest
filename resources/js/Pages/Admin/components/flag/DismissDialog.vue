<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { router } from '@inertiajs/vue3';

const props = withDefaults(
  defineProps<{
    flagId?: number | string | number[] | string[];
    disabled: boolean;
    label?: string;
  }>(),
  {
    label: 'Dismiss report',
    active: true,
  },
);

const dialog = ref(false);
const loading = ref(false);
const toast = useToasts();

function dismiss() {
  if (Array.isArray(props.flagId)) {
    router.delete(
      route('admin.flags.dismiss.bulk', { flag_ids: props.flagId }),
      {
        onStart: () => (loading.value = true),
        onSuccess: () => toast.success('Successfully dismissed report'),
        onFinish: () => {
          loading.value = false;
          dialog.value = false;
        },
        onError(err) {
          for (const key in err) {
            toast.error(err[key]);
          }
        },
      },
    );
  } else {
    router.delete(route('admin.flags.dismiss', { id: props.flagId }), {
      onStart: () => (loading.value = true),
      onSuccess: () => toast.success('Successfully dismissed report'),
      onFinish: () => {
        loading.value = false;
        dialog.value = false;
      },
      onError(err) {
        for (const key in err) {
          toast.error(err[key]);
        }
      },
    });
  }
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
        :disabled
      >
        {{ label }}
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
