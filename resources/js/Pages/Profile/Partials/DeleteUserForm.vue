<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  password: '',
});

const deleteUser = () => {
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value?.focus(),
    onFinish: () => {
      form.reset();
    },
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
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

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Are you sure you want to delete your account?
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          Once your account is deleted, all of its resources and data will be
          permanently deleted. Please enter your password to confirm you would
          like to permanently delete your account.
        </p>

        <div class="mt-6">
          <InputLabel for="password" value="Password" class="sr-only" />

          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="Password"
            @keyup.enter="deleteUser"
          />

          <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

          <DangerButton
            class="ms-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteUser"
          >
            Delete Account
          </DangerButton>
        </div>
      </div>
    </Modal>
  </section>
</template>
