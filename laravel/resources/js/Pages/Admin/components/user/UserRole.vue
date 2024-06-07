<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { Role, User } from '@/types';
import { required } from '@/utils';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  user: User;
  roles: Role[];
}>();

const isValid = ref(false);
const toast = useToasts();

const form = useForm({
  roles: props.user.roles,
});

function submit() {
  if (isValid.value)
    form.patch(route('admin.users.edit', { id: props.user.id }), {
      onSuccess: () => toast.success('Successfully updated user roles'),
      onError(err) {
        for (const key in err) {
          toast.error(err[key]);
        }
      },
    });
}
</script>

<template>
  <v-form v-model="isValid" @submit.prevent="submit">
    <v-autocomplete
      v-model="form.roles"
      :items="roles"
      item-title="name"
      item-value="id"
      multiple
      chips
    />
    <primary-btn
      type="submit"
      :disabled="!form.isDirty"
      :loading="form.processing"
    >
      Update Roles
    </primary-btn>
  </v-form>
</template>
