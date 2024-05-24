<script setup lang="ts">
import { PasswordInput } from '@/Components';
import { useToasts } from '@/store/toasts';
import { required, validEmail } from '@/utils';
import { useForm } from '@inertiajs/vue3';

const isValid = ref(false);
const toast = useToasts();

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function handleSubmit() {
  if (isValid.value) {
    form.post(route('admin.users.create'), {
      onSuccess: () => toast.success('Successfully created user!'),
      onError(err) {
        for (const key in err) {
          toast.error(err[key]);
        }
      },
    });
  }
}

function passMatch(input: string) {
  return input === form.password || 'Passwords do not match.';
}
</script>

<template>
  <v-form v-model="isValid" @submit.prevent="handleSubmit">
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
    <password-input
      label="Password"
      v-model="form.password"
      :rules="[required]"
    />
    <password-input
      label="Confirm Password"
      v-model="form.password_confirmation"
      :rules="[required, passMatch]"
    />
    <div class="d-flex justify-center">
      <primary-btn
        type="submit"
        :loading="form.processing"
        :disabled="!form.isDirty && !isValid"
      >
        Submit
      </primary-btn>
    </div>
  </v-form>
</template>
