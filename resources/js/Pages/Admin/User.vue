<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { Role, User } from '@/types';
import UserInfo from './components/user/UserInfo.vue';
import UserRole from './components/user/UserRole.vue';
import UserPassword from './components/user/UserPassword.vue';
import { router } from '@inertiajs/vue3';
import { useToasts } from '@/store/toasts';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
  user: User;
  roles: Role[];
}>();

const loading = ref(false);
const toast = useToasts();
const dialog = ref(false);

function deleteUser() {
  router.delete(route('admin.users.delete', { id: props.user }), {
    onStart: () => (loading.value = true),
    onFinish: () => (loading.value = false),
    onSuccess() {
      dialog.value = false;
      toast.success('Successfully delete user.');
    },
    onError(err) {
      dialog.value = false;
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}
</script>

<template>
  <Head :title="`${user.name} - Admin`" />
  <AdminLayout>
    <v-row>
      <v-col cols="12">
        <v-card title="User information">
          <v-card-text>
            <UserInfo :user />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12">
        <v-card title="Role">
          <v-card-text>
            <UserRole :user :roles />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12">
        <v-card title="Password">
          <v-card-text>
            <UserPassword :user />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col>
        <v-card title="Delete">
          <v-card-text class="text-caption text-error">
            <v-alert color="warning" icon="mdi-alert" density="compact">
              Danger! Deleting a user is permanent
            </v-alert>
          </v-card-text>
          <v-card-text>
            <v-dialog max-width="400" v-model="dialog">
              <template #activator="{ props }">
                <v-btn
                  v-bind="props"
                  variant="flat"
                  color="error"
                  prepend-icon="mdi-delete"
                >
                  Delete user
                </v-btn>
              </template>

              <v-card title="Delete User">
                <v-card-text>
                  <p>
                    Are you sure you want to delete this user? This action is
                    not reversable.
                  </p>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn @click="dialog = false">Cancel</v-btn>
                  <v-btn
                    color="error"
                    variant="flat"
                    :loading
                    @click="deleteUser"
                  >
                    Delete User
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </AdminLayout>
</template>
