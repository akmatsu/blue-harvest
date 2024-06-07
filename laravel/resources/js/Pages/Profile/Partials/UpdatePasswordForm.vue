<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { required } from '@/utils';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value?.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value?.focus();
      }
    },
  });
};
</script>

<template>
  <section class="my-4">
    <h2>Update Password</h2>

    <p class="mb-4 text-subtitle-2">
      Ensure your account is using a long, random password to stay secure.
    </p>

    <v-form @submit.prevent="updatePassword">
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
      />

      <v-text-field
        id="password_confirmation"
        v-model="form.password_confirmation"
        label="Confirm new Password"
        type="password"
        autocomplete="new-password"
      />

      <v-btn color="primary" :disabled="form.processing">Update Password</v-btn>

      <!-- <Transition
        enter-active-class="transition ease-in-out"
        enter-from-class="opacity-0"
        leave-active-class="transition ease-in-out"
        leave-to-class="opacity-0"
      >
        <p
          v-if="form.recentlySuccessful"
          class="text-sm text-gray-600 dark:text-gray-400"
        >
          Saved.
        </p>
      </Transition> -->
    </v-form>
  </section>
</template>
