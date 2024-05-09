<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { required } from '@/utils';

defineProps<{
  status?: string;
}>();

const isValid = ref(false);

const form = useForm({
  email: '',
});

const submit = () => {
  if (isValid.value) {
    form.post(route('password.email'));
  }
};
</script>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <v-card max-width="500" class="mx-auto">
      <v-card-title class="text-center"> Forgot Password </v-card-title>
      <v-card-text>
        <p class="mb-4">
          Forgot your password? No problem. Just let us know your email address
          and we will email you a password reset link that will allow you to
          choose a new one.
        </p>

        <div
          v-if="status"
          class="mb-4 font-medium text-sm text-green-600 dark:text-green-400"
        >
          {{ status }}
        </div>

        <v-form v-model="isValid" @submit.prevent="submit">
          <v-text-field
            id="email"
            v-model="form.email"
            label="Email"
            type="email"
            class="mt-1 block w-full"
            autofocus
            autocomplete="username"
            :rules="[required]"
          />

          <div class="d-flex justify-center">
            <v-btn color="primary" :loading="form.processing">
              Email Password Reset Link
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </GuestLayout>
</template>
