<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { router } from '@inertiajs/vue3';

const props = withDefaults(
  defineProps<{
    flagId?: number | string | number[] | string[];
    disabled?: boolean;
    label?: string;
  }>(),
  {
    label: 'Delete Item',
  },
);
const dialog = ref(false);
const loading = ref(false);
const toast = useToasts();

function deleteItem() {
  if (Array.isArray(props.flagId)) {
    router.delete(
      route('admin.flags.delete.bulk', { flag_ids: props.flagId }),
      {
        onStart: () => (loading.value = true),
        onFinish: () => {
          loading.value = false;
          dialog.value = false;
        },
        onSuccess: () =>
          toast.success('Successfully deleted the flagged items.'),
        onError(err) {
          for (const key in err) {
            toast.error(err[key]);
          }
        },
      },
    );
  } else {
    router.delete(route('admin.flags.delete', { id: props.flagId }), {
      onStart: () => (loading.value = true),
      onFinish: () => (loading.value = false),
      onSuccess: () => toast.success('Successfully deleted the flagged item.'),
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
        variant="flat"
        v-bind="props"
        prepend-icon="mdi-delete"
        color="error"
        :disabled
      >
        {{ label }}
      </v-btn>
    </template>
    <v-card title="Delete reported item">
      <v-card-text>
        <p>
          Are you sure you want to delete this item? This action is not
          reversable.
        </p>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="dialog = false" :disabled="loading">Cancel</v-btn>
        <danger-btn @click="deleteItem" prepend-icon="mdi-delete" :loading>
          Delete Item
        </danger-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
