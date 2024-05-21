<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { User } from '@/types';
import { required, validEmail } from '@/utils';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  user: User;
}>();
const toast = useToasts();
const isValid = ref(false);

const form = useForm({
  name: props.user.name,
  email: props.user.email,
});

function submit() {
  if (isValid.value) {
    form.patch(route('admin.users.edit', { id: props.user.id }), {
      onSuccess: () => toast.success('Successfully updated user!'),
      onError: (err) => {
        for (const key in err) {
          toast.error(err[key]);
        }
      },
    });
  }
}
</script>

<template>
  <v-form v-model="isValid" @submit.prevent="submit">
    <v-text-field
      label="Name"
      v-model="form.name"
      :rules="[required]"
    ></v-text-field>
    <v-text-field
      label="Email"
      v-model="form.email"
      :rules="[required, validEmail]"
    ></v-text-field>
    <primary-btn
      type="submit"
      :disabled="!form.isDirty"
      :loading="form.processing"
    >
      Update Info
    </primary-btn>
  </v-form>
</template>
