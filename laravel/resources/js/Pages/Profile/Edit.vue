<script setup lang="ts">
import CoreLayout from '@/Layouts/CoreLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
  mustVerifyEmail?: boolean;
  status?: string;
}>();
</script>

<template>
  <Head title="Profile" />
  <CoreLayout>
    <v-card>
      <v-card-text>
        <div>
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </div>

        <div>
          <UpdatePasswordForm />
        </div>

        <div>
          <DeleteUserForm />
        </div>
      </v-card-text>
    </v-card>
  </CoreLayout>
</template>
