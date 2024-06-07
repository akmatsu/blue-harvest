<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';
import { required } from '@/utils';
import { PasswordInput } from '@/Components';
import { useToasts } from '@/store/toasts';

const props = defineProps<{
  email: string;
  token: string;
}>();

const toast = useToasts();
const isValid = ref(false);

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

function passwordsMatch(val: string) {
  return val === form.password || 'Passwords do not match.';
}

const submit = () => {
  if (isValid.value) {
    form.post(route('password.store'), {
      onSuccess: () => toast.success('Password successfully reset!'),
      onError: (err) => toast.error(err.message),
      onFinish: () => {
        form.reset('password', 'password_confirmation');
      },
    });
  }
};
</script>

<template>
  <AuthLayout>
    <Head title="Reset Password" />
    <v-card max-width="500" class="mx-auto">
      <v-card-text>
        <v-form v-model="isValid" @submit.prevent="submit">
          <v-text-field
            v-model="form.email"
            label="Email"
            type="email"
            :rules="[required]"
          ></v-text-field>
          <PasswordInput
            v-model="form.password"
            label="Password"
            :rules="[required]"
          ></PasswordInput>
          <PasswordInput
            v-model="form.password_confirmation"
            label="Confirm Password"
            :rules="[required, passwordsMatch]"
          />

          <div class="d-flex justify-center">
            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              :disabled="!isValid"
            >
              Reset Password
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </AuthLayout>
</template>
