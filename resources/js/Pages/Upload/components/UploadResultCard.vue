<script lang="ts" setup>
import { useToasts } from '@/store/toasts';
import { Image } from '@/types';
import { imageDelete, required } from '@/utils';
import { useForm } from '@inertiajs/vue3';
import { TagSelection } from '@/Components';

const props = defineProps<{
  image: Image;
  selection: Number[];
}>();

const selected = defineModel('selected', {
  type: Boolean,
  default: false,
});

const form = useForm({
  name: props.image.name,
  tags: props.image.tags?.map((tag) => tag.name),
});

watch(
  () => props.image,
  (image) => {
    form.name = image.name;
    form.tags = image.tags?.map((tag) => tag.name);
  },
);

const toast = useToasts();

function handleSubmit() {
  form.patch(`/images/${props.image.id}`, {
    data: getChangedFields(),
    preserveScroll: true,
    onSuccess: () => toast.success('Successfully updated the image.'),
    onError: (err) => toast.error(err.message),
  });
}

watch(
  () => props.selection,
  (selection) => {
    selected.value = selection.includes(props.image.id);
  },
);

function getChangedFields() {
  const changedFields: { name?: string; tags?: string[] } = {};
  if (form.name !== props.image.name) {
    changedFields.name = form.name;
  }

  if (form.tags !== props.image.tags?.map((tag) => tag.name)) {
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
          <v-checkbox v-model="selected" class="mr-2"></v-checkbox>
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
    <v-form class="mt-2" @submit.prevent="handleSubmit">
      <v-text-field
        v-model="form.name"
        label="Name"
        :rules="[required]"
      ></v-text-field>
      <tag-selection v-model="form.tags" />

      <div class="d-flex">
        <v-spacer></v-spacer>
        <v-dialog max-width="400px">
          <template #activator="{ props }">
            <v-btn
              color="error"
              variant="text"
              prepend-icon="mdi-delete"
              v-bind="props"
            >
              Delete
            </v-btn>
          </template>
          <template #default="{ isActive }">
            <v-card title="Delete Image">
              <v-card-text>
                <p>
                  Are you sure you want to delete this image? This is not
                  reversible.
                </p>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="isActive.value = false">Cancel</v-btn>
                <v-btn
                  variant="flat"
                  color="error"
                  @click="imageDelete(image.id)"
                >
                  Delete
                </v-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
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
