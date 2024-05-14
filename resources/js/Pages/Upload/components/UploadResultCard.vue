<script lang="ts" setup>
import { Image, Tag } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { formatBytes, imageDelete } from '@/utils';
import { required } from '@/utils';
import { useToasts } from '@/store/toasts';

const props = defineProps<{
  image: Image;
  tags: Tag[];
}>();

const form = useForm({
  name: props.image.name,
  tags: props.image.tags?.map((tag) => tag.id),
});

const toast = useToasts();

function handleSubmit() {
  form.post(`/images/${props.image.id}`, {
    data: getChangedFields(),
    preserveScroll: true,
    onSuccess: () => toast.success('Successfully updated the image.'),
    onError: (err) => toast.error(err.message),
  });
}

function getChangedFields() {
  const changedFields: { name?: string; tags?: number[] } = {};
  if (form.name !== props.image.name) {
    changedFields.name = form.name;
  }

  if (form.tags !== props.tags.map((tag) => tag.id)) {
    changedFields.tags = form.tags;
  }

  return changedFields;
}
</script>

<template>
  <v-list-item>
    <template #prepend>
      <v-dialog width="600" max-width="100%">
        <template #activator="{ props }">
          <div>
            <v-img
              :src="image.url"
              max-width="100%"
              width="200"
              cover
              :aspect-ratio="1"
              class="mr-2"
              style="cursor: pointer"
              v-bind="props"
            ></v-img>
            <p class="text-caption text-center">Click to expand</p>
          </div>
        </template>
        <v-card>
          <v-img :src="image.url"></v-img>
        </v-card>
      </v-dialog>
    </template>
    <v-form @submit.prevent="handleSubmit" class="mt-2">
      <v-text-field
        v-model="form.name"
        label="Name"
        :rules="[required]"
      ></v-text-field>
      <v-autocomplete
        v-model="form.tags"
        label="tags"
        multiple
        chips
        item-value="id"
        item-title="name"
        :items="tags"
      />
      <div class="d-flex">
        <v-spacer></v-spacer>
        <primary-btn
          type="submit"
          :disabled="!form.isDirty"
          :loading="form.processing"
        >
          Update
        </primary-btn>
      </div>
    </v-form>
  </v-list-item>
</template>
