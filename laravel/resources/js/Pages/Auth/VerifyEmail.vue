<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToasts } from '@/store/toasts';

const props = defineProps<{
  status?: string;
}>();

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const toast = useToasts();

watch(
  () => props.status,
  (val) => {
    if (val === 'verification-link-sent') {
      toast.success('Verification link sent!');
    }
  },
);
</script>

<template>
  <AuthLayout>
    <Head title="Email Verification" />
    <v-card title="Email Verification" max-width="500" class="mx-auto">
      <v-card-text>
        Thanks for signing up! Before getting started, could you verify your
        email address by clicking on the link we just emailed to you? If you
        didn't receive the email, we will gladly send you another.
      </v-card-text>

      <v-form @submit.prevent="submit">
        <v-card-actions>
          <v-spacer></v-spacer>
          <Link :href="route('logout')" method="post" as="button">
            <v-btn color="primary">Log Out</v-btn>
          </Link>
          <v-btn
            type="submit"
            color="primary"
            variant="flat"
            :loading="form.processing"
          >
            Resend Verification Email
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </AuthLayout>
</template>
