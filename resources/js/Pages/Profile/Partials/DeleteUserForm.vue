<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  password: '',
});

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onError: () => passwordInput.value?.focus(),
    onFinish: () => {
      form.reset();
    },
  });
};
</script>

<template>
  <section class="my-4">
    <header>
      <h2>Delete Account</h2>

      <p class="mb-4 text-subtitle-2">
        Once your account is deleted, all of its resources and data will be
        permanently deleted. Before deleting your account, please download any
        data or information that you wish to retain.
      </p>
    </header>

    <v-dialog max-width="400">
      <template #activator="{ props }">
        <v-btn color="error" v-bind="props">Delete Account</v-btn>
      </template>
      <template #default="{ isActive }">
        <v-card title="Delete Account Confirmation">
          <v-card-text>
            <p class="mb-5">
              Once your account is deleted, all of its resources and data will
              be permanently deleted. Please enter your password to confirm you
              would like to permanently delete your account.
            </p>

            <v-text-field
              id="password"
              ref="passwordInput"
              v-model="form.password"
              label="Password"
              type="password"
              placeholder="Password"
              @keyup.enter="deleteUser"
            />
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="isActive.value = false">Cancel</v-btn>
            <v-btn color="error" variant="flat" :loading="form.processing">
              Delete Account
            </v-btn>
          </v-card-actions>
        </v-card>
      </template>
    </v-dialog>
  </section>
</template>
