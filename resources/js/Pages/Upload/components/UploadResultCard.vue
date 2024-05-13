<script lang="ts" setup>
import { Image, Tag } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { formatBytes } from '@/utils';
import { required } from '@/utils';

const props = defineProps<{
  image: Image;
  tags: Tag[];
}>();

const form = useForm({
  name: props.image.name,
  tags: props.image.tags,
});
</script>

<template>
  <v-card>
    <v-card-actions>
      <VSpacer />
      <v-menu>
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            variant="flat"
            color="primary"
            append-icon="mdi-chevron-down"
          >
            Optimized Images
          </v-btn>
        </template>
        <v-list>
          <v-dialog v-for="oi in image.optimized_images" width="500">
            <template #activator="{ props }">
              <v-list-item v-bind="props">{{ oi?.size }}</v-list-item>
            </template>
            <v-card>
              <v-img :src="oi.url"></v-img>
              <v-card-title>{{ oi.size }}</v-card-title>
              <v-card-subtitle>
                {{ oi.width }} x {{ oi.height }} ({{
                  formatBytes(oi.file_size)
                }})
              </v-card-subtitle>
              <v-card-text></v-card-text>
            </v-card>
          </v-dialog>
        </v-list>
      </v-menu>
    </v-card-actions>
    <v-img :src="image.url"></v-img>
    <v-card-title> Confirm Image </v-card-title>
    <v-card-text>
      <v-form @submit.prevent>
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
        <primary-btn type="submit" :disabled="!form.isDirty">
          Update
        </primary-btn>
      </v-form>
    </v-card-text>
  </v-card>
</template>
