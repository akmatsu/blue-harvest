<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToasts } from '@/store/toasts';
import { required } from '@/utils';
import { PasswordInput } from '@/Components';

const isValid = ref(false);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const toast = useToasts();

function passwordsMatch() {
  return (
    form.password === form.password_confirmation || 'Passwords do not match.'
  );
}

const submit = () => {
  if (isValid.value) {
    form.post(route('register'), {
      onError: (err) => toast.error(err.message),
      onFinish: () => {
        form.reset('password', 'password_confirmation');
      },
    });
  }
};
</script>

<template>
  <Head title="Register" />
  <AuthLayout>
    <v-card title="Register" width="350" max-width="100%">
      <v-card-text>
        <v-form v-model="isValid" @submit.prevent="submit">
          <v-text-field
            v-model="form.name"
            label="Name"
            :rules="[required]"
          ></v-text-field>
          <v-text-field
            v-model="form.email"
            type="email"
            label="Email"
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

          <div class="d-flex flex-column align-center justify-center">
            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              :disabled="!isValid"
            >
              Register
            </v-btn>

            <v-btn href="/login" @click.prevent.stop="$inertia.get('/login')">
              Already Registered?
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </AuthLayout>
</template>
