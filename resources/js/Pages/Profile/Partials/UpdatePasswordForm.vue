<script setup lang="ts">
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const isValid = ref(false);
const formRef = ref();

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const toast = useToasts();

const updatePassword = () => {
  if (isValid.value) {
    form.put(route('password.update'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        formRef.value.reset();
        toast.success('Successfully updated password!');
      },
      onError: () => {
        for (const key in form.errors) {
          const msg = form.errors[key as keyof typeof form.errors];
          if (msg) toast.error(msg);
        }
      },
    });
  }
};

function matchPassword(val: string) {
  return val === form.password || 'Passwords do not match.';
}
</script>

<template>
  <section class="my-4">
    <h2>Update Password</h2>

    <p class="mb-4 text-subtitle-2">
      Ensure your account is using a long, random password to stay secure.
    </p>

    <v-form @submit.prevent="updatePassword" v-model="isValid" ref="formRef">
      <v-text-field
        ref="currentPasswordInput"
        v-model="form.current_password"
        label="Current Password"
        type="password"
        autocomplete="current-password"
        :rules="[required]"
      />

      <v-text-field
        id="password"
        ref="passwordInput"
        v-model="form.password"
        label="New Password"
        type="password"
        autocomplete="new-password"
        :rules="[required]"
      />

      <v-text-field
        id="password_confirmation"
        v-model="form.password_confirmation"
        label="Confirm new Password"
        type="password"
        autocomplete="new-password"
        :rules="[required, matchPassword]"
      />

      <v-btn
        color="primary"
        :loading="form.processing"
        :disabled="!form.isDirty"
        type="submit"
      >
        Update Password
      </v-btn>
    </v-form>
  </section>
</template>
