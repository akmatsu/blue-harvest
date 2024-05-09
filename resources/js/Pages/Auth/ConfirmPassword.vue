<script setup lang="ts">
import { PasswordInput } from '@/Components';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';

import { Head, useForm } from '@inertiajs/vue3';

const isValid = ref(false);

const form = useForm({
  password: '',
});

const toast = useToasts();

const submit = () => {
  if (isValid) {
    form.post(route('password.confirm'), {
      onSuccess: () => toast.success('Success!'),
      onError: (err) => toast.error(err.message),
      onFinish: () => {
        form.reset();
      },
    });
  }
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />

    <v-card title="Confirm Your Password" max-width="500" class="mx-auto">
      <v-card-text>
        This is a secure area of the application. Please confirm your password
        before continuing.
      </v-card-text>
      <v-card-text>
        <v-form v-model="isValid" @submit.prevent="submit">
          <PasswordInput
            v-model="form.password"
            label="Password"
            :rules="[required]"
          />

          <div class="d-flex justify-center">
            <v-btn color="primary" :loading="form.processing">Confirm</v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </GuestLayout>
</template>
