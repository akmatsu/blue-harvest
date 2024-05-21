<script lang="ts" setup>
import { PasswordInput } from '@/Components';
import { useToasts } from '@/store/toasts';
import { User } from '@/types';
import { required } from '@/utils';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  user: User;
}>();

const toast = useToasts();
const isValid = ref(false);

const form = useForm({
  password: '',
  password_confirmation: '',
});

function submit() {
  form.patch(route('admin.users.edit', { id: props.user.id }), {
    onSuccess: () => toast.success('Successfully updated password.'),
    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}

function passMatch(input: string) {
  return form.password === input || 'Passwords do not match.';
}
</script>

<template>
  <v-form v-model="isValid" @submit.prevent="submit">
    <PasswordInput
      label="New Password"
      :rules="[required]"
      v-model="form.password"
    />
    <PasswordInput
      label="Confirm Password"
      :rules="[required, passMatch]"
      v-model="form.password_confirmation"
    />
    <primary-btn
      type="submit"
      :disabled="!isValid || !form.isDirty"
      :loading="form.processing"
    >
      Update Password
    </primary-btn>
  </v-form>
</template>
