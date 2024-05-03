<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { required } from '@/utils';

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

const submit = () => {
  if (isValid.value)
    form.post(route('login'), {
      onFinish: () => {
        form.reset('password');
      },
    });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <v-card max-width="500" class="mx-auto">
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

          <v-checkbox
            v-model:checked="form.remember"
            label="Remember me"
            name="remember"
          />

          <div class="d-flex flex-column justify-center align-center">
            <v-btn
              type="submit"
              color="primary"
              :loading="form.processing"
              :disabled="!isValid"
            >
              Log in
            </v-btn>

            <Link v-if="canResetPassword" :href="route('password.request')">
              <v-btn>Forgot your password?</v-btn>
            </Link>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </GuestLayout>
</template>
