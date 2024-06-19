<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
  canResetPassword?: boolean;
  status?: string;
}>();

const isValid = ref(false);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const toast = useToasts();

const submit = () => {
  if (isValid.value)
    form.post(route('login'), {
      onSuccess: () => toast.success('Successfully logged in!'),
      onError: (err) =>
        toast.error(err.email || err.password || 'An error occurred.'),
      onFinish: () => {
        form.reset('password');
      },
    });
};
</script>

<template>
  <AuthLayout>
    <Head title="Log in" />

    <v-card width="350" max-width="100%">
      <v-card-title class="text-center"> Log In </v-card-title>
      <v-card-text>
        <v-form v-model="isValid" @submit.prevent="submit">
          <v-text-field
            id="email"
            v-model="form.email"
            label="email"
            type="email"
            :rules="[required]"
            autocomplete="username"
          />

          <v-text-field
            id="password"
            v-model="form.password"
            label="password"
            type="password"
            :rules="[required]"
            autocomplete="current-password"
          />
          <div class="d-flex">
            <v-spacer></v-spacer>
            <secondary-btn
              v-if="canResetPassword"
              :href="route('password.request')"
              size="small"
              @click.prevent.stop="$inertia.get(route('password.request'))"
            >
              Forgot your password?
            </secondary-btn>
          </div>

          <v-checkbox
            v-model:checked="form.remember"
            label="Remember me"
            name="remember"
          />

          <div class="d-flex flex-column justify-center align-center">
            <PrimaryBtn
              type="submit"
              color="primary"
              :loading="form.processing"
              :disabled="!isValid"
            >
              Log in
            </PrimaryBtn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </AuthLayout>
</template>
