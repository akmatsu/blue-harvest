<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { Image, Restriction } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  flagId: number | string;
  restrictions: Restriction[];
  image?: Image;
}>();
const dialog = ref(false);

const toast = useToasts();
const isValid = ref(false);

const form = useForm({
  restriction_ids: props.image?.restrictions?.map((r) => r.id) || [],
});

function handleSubmit() {
  if (isValid.value) {
    form.post(route('admin.flags.restrict', { id: props.flagId }), {
      onSuccess() {
        toast.success('Successfully restricted this item.');
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
  <v-dialog max-width="400" v-model="dialog" :persistent="form.processing">
    <template #activator="{ props }">
      <v-btn
        variant="flat"
        v-bind="props"
        prepend-icon="mdi-cancel"
        color="warning"
      >
        Restrict Item
      </v-btn>
    </template>
    <v-card title="Restrict reported item">
      <v-card-text>
        <p>
          Please describe a basic reason why it's restricted who they should
          contact before using it.
        </p>
        <v-form @submit.prevent="handleSubmit" v-model="isValid">
          <v-autocomplete
            counter
            v-model="form.restriction_ids"
            :items="restrictions"
            item-title="name"
            item-value="id"
            multiple
            chips
          ></v-autocomplete>
          <div class="d-flex justify-end">
            <v-btn
              @click="dialog = false"
              :disabled="form.processing"
              variant="text"
            >
              Cancel
            </v-btn>
            <v-btn
              prepend-icon="mdi-cancel"
              :loading="form.processing"
              :disabled="!form.isDirty"
              type="submit"
              color="warning"
            >
              Restrict Item
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
