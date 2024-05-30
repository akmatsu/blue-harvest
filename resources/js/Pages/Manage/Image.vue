<script lang="ts" setup>
import { CoreLayout } from '@/Layouts';
import { useToasts } from '@/store/toasts';
import { Image, Restriction, Tag } from '@/types';
import { useForm, Head, router } from '@inertiajs/vue3';

const props = defineProps<{
  image: Image;
  tags: Tag[];
}>();

const toast = useToasts();
const deleteLoading = ref(false);

const updateForm = useForm({
  name: props.image.name,
  tags: props.image.tags?.map((t) => t.id) || [],
});

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
    <v-card :title="image.name">
      <v-img :src="image.url" max-width="100%" max-height="700px"></v-img>
      <v-card-text>
        <h6 class="text-h6 mb-4">Image info</h6>
        <v-form @submit.prevent="handleUpdate" class="mb-8">
          <v-text-field label="Name" v-model="updateForm.name"></v-text-field>
          <v-autocomplete
            label="Tags"
            v-model="updateForm.tags"
            :items="tags"
            item-title="name"
            item-value="id"
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
                <v-btn @click="isActive.value = false"></v-btn>
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
