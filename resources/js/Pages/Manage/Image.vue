<script lang="ts" setup>
import { CoreLayout } from '@/Layouts';
import { useToasts } from '@/store/toasts';
import { Image, Tag } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ViewOptions } from '../Image/components';
import TagSelection from '@/Components/TagSelection.vue';

const props = defineProps<{
  image: Image;
}>();

const toast = useToasts();
const deleteLoading = ref(false);

const updateForm = useForm<{
  name: string;
  description: string;
  tags: string[];
  status: Image['status'];
  attribution: string;
}>({
  name: props.image.name,
  description: props.image.description || '',
  tags: props.image.tags?.map((t) => t.name) || [],
  status: props.image.status,
  attribution: props.image.attribution,
});

const statusOptions: Image['status'][] = ['private', 'public'];

const requestForm = useForm({
  flaggable_id: props.image.id,
  flaggable_type: 'App\\Models\\Image',
  reason: 'User requested Admin review to publish image.',
});

function requestAdminReview() {
  requestForm.post(route('flags.store'), {
    preserveScroll: true,
    // preserveState: true,
    onSuccess: () => toast.success('Successfully requested Admin review.'),
    onError(err) {
      for (const key in err) {
        toast.error(err[key]);
      }
    },
  });
}

function handleUpdate() {
  updateForm.patch(route('images.update', { id: props.image.id }), {
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

function handleDelete() {
  router.delete(route('images.delete', { id: props.image.id }), {
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
  <Head :title="`Image ${image.name}`" />
  <CoreLayout>
    <v-card>
      <v-card-actions>
        <v-spacer> </v-spacer>
        <ViewOptions :image="image" />
      </v-card-actions>
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
          <v-row>
            <v-col cols="auto">
              <v-select
                :disabled="
                  image.status !== 'public' && image.status !== 'private'
                "
                label="Status"
                v-model="updateForm.status"
                :items="statusOptions"
              ></v-select>
            </v-col>
            <v-col>
              <primary-btn
                v-if="image.status === 'unprocessed'"
                @click="requestAdminReview"
                :loading="requestForm.processing"
              >
                Request admin review to publish this image
              </primary-btn>
            </v-col>
          </v-row>

          <TagSelection v-model="updateForm.tags" />

          <primary-btn
            type="submit"
            :disabled="!updateForm.isDirty"
            :loading="updateForm.processing"
          >
            Update Image
          </primary-btn>
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
  </CoreLayout>
</template>
