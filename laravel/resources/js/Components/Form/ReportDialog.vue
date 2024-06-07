<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  itemId: number;
  itemType: string;
}>();
const isValid = ref(false);
const toast = useToasts();
const dialog = ref(false);

const form = useForm({
  flaggable_id: props.itemId,
  flaggable_type: props.itemType,
  reason: '',
});

function maxLength(val: string) {
  return val.length <= 255 || 'Max 255 characters.';
}

function handleSubmit() {
  if (isValid) {
    form.post(route('flags.store'), {
      onSuccess() {
        dialog.value = false;
        form.reset();
        toast.success('Thank you. Your report has been submitted.');
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
  <v-dialog
    max-width="500"
    v-model="dialog"
    :persistent="form.processing"
    v-if="$page.props.auth.user"
  >
    <template #activator="{ props }">
      <slot :props="props">
        <v-btn
          color="error"
          variant="flat"
          prepend-icon="mdi-flag"
          v-bind="props"
        >
          Report
        </v-btn>
      </slot>
    </template>
    <v-card :title="`Report ${itemType}`">
      <v-card-text>
        <v-form v-model="isValid" @submit.prevent="handleSubmit">
          <v-textarea
            v-model="form.reason"
            label="Reason"
            counter
            :rules="[required, maxLength]"
          ></v-textarea>
          <div class="d-flex flex-column justify-center align-center">
            <primary-btn
              type="submit"
              :disabled="!isValid || !form.isDirty"
              :loading="form.processing"
            >
              Submit Report
            </primary-btn>
            <v-btn @click="dialog = false" :disabled="form.processing"
              >Cancel</v-btn
            >
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
