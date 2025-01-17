<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { useToasts } from '@/store/toasts';
import { Image, Restriction, Tag } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps<{
  image: Image;
  restrictions: Restriction[];
  tags: Tag[];
}>();

const toast = useToasts();
const deleteLoading = ref(false);

const updateForm = useForm({
  name: props.image.name,
  description: props.image.description || '',
  tags: props.image.tags?.map((t) => t.name) || [],
  status: props.image.status,
  attribution: props.image.attribution,
});

const restrictionForm = useForm({
  restriction_ids: props.image.restrictions?.map((r) => r.id) || [],
});

const statusOptions: Image['status'][] = [
  // 'unprocessed',
  // 'pending processing',
  // 'processing',
  // 'pending review',
  'private',
  'public',
];

function handleUpdate() {
  updateForm.patch(route('admin.images.update', { id: props.image.id }), {
    preserveScroll: true,
    preserveState: true,
    onSuccess() {
      toast.success('Successfully updated image information.');
    },

    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}

function handleRestrict() {
  restrictionForm.post(route('admin.images.restrict', { id: props.image.id }), {
    preserveScroll: true,
    preserveState: true,
    onSuccess() {
      toast.success('Successfully restricted image.');
    },

    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}

function handleDelete() {
  router.delete(route('admin.images.delete', { id: props.image.id }), {
    onStart: () => (deleteLoading.value = true),
    onSuccess: () => toast.success('Image was successfully deleted.'),
    onError(e) {
      for (const k in e) {
        toast.success(e[k]);
      }
    },
    onFinish: () => (deleteLoading.value = false),
  });
}
</script>

<template>
  <Head :title="`Image ${image.name} - Admin`" />
  <AdminLayout>
    <v-card :title="image.name">
      <v-img :src="image.url" max-width="100%" max-height="700px"></v-img>
      <v-card-text>
        <h6 class="text-h6 mb-4">Image info</h6>
        <v-form @submit.prevent="handleUpdate" class="mb-8">
          <v-text-field label="Name" v-model="updateForm.name"></v-text-field>
          <v-text-field
            label="Attribution"
            v-model="updateForm.attribution"
          ></v-text-field>
          <v-text-field
            label="Description"
            v-model="updateForm.description"
          ></v-text-field>
          <v-select
            label="Status"
            v-model="updateForm.status"
            :items="statusOptions"
          ></v-select>
          <v-autocomplete
            label="Tags"
            v-model="updateForm.tags"
            :items="tags"
            item-title="name"
            item-value="name"
            multiple
            chips
          ></v-autocomplete>
          <primary-btn
            type="submit"
            :disabled="!updateForm.isDirty"
            :loading="updateForm.processing"
          >
            Update Image
          </primary-btn>
        </v-form>
        <h6 class="text-h6 mb-4">Image Restrictions</h6>
        <v-form @submit.prevent="handleRestrict">
          <v-autocomplete
            label="Restrictions"
            v-model="restrictionForm.restriction_ids"
            :items="restrictions"
            item-title="name"
            item-value="id"
            multiple
            chips
          ></v-autocomplete>
          <v-btn
            variant="flat"
            color="warning"
            type="submit"
            :disabled="!restrictionForm.isDirty"
            :loading="restrictionForm.processing"
          >
            Restrict Image
          </v-btn>
        </v-form>
        <v-dialog max-width="400" :persistent="deleteLoading">
          <template #activator="{ props }">
            <danger-btn v-bind="props">Delete Image</danger-btn>
          </template>
          <template #default="{ isActive }">
            <v-card title="Delete Image">
              <v-card-text>
                Are you sure you want to delete this image? This action is
                permanent and cannot be reversed.
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="isActive.value = false">Cancel</v-btn>
                <danger-btn @click="handleDelete" :loading="deleteLoading">
                  Delete Image
                </danger-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
