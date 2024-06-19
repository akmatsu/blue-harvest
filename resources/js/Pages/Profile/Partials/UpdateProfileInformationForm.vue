<script setup lang="ts">
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
  mustVerifyEmail?: boolean;
  status?: string;
}>();

const user = usePage().props.auth.user;

const form = useForm({
  name: user.name,
  email: user.email,
});

const toast = useToasts();

function handleSubmit() {
  form.patch(route('profile.update'), {
    onSuccess: () => toast.success('Successfully updated profile information.'),
    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}
</script>

<template>
  <section class="my-4">
    <h2>Profile Information</h2>

    <p class="mb-4 text-subtitle-2">
      Update your account's profile information and email address.
    </p>

    <v-form @submit.prevent="handleSubmit">
      <v-text-field
        v-model="form.name"
        label="Name"
        required
        :rules="[required]"
        autocomplete="name"
      />

      <v-text-field
        v-model="form.email"
        label="Email"
        type="email"
        autocomplete="username"
        :rules="[required]"
      />

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <v-btn
          color="primary"
          :loading="form.processing"
          :disabled="!form.isDirty"
          type="submit"
        >
          Save
        </v-btn>
      </div>
    </v-form>
  </section>
</template>
