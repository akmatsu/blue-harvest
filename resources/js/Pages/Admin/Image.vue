<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { useToasts } from '@/store/toasts';
import { Image, Restriction, Tag } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  image: Image;
  restrictions: Restriction[];
  tags: Tag[];
}>();

const toast = useToasts();

const updateForm = useForm({
  name: props.image.name,
  tags: props.image.tags?.map((t) => t.id) || [],
});

const restrictionForm = useForm({
  restriction_ids: props.image.restrictions?.map((r) => r.id) || [],
});

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
</script>

<template>
  <AdminLayout>
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
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
