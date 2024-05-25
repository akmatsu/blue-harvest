<script lang="ts" setup>
import { AdminLayout } from '@/Layouts';
import { Image, Restriction, Tag } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
  image: Image;
  restrictions: Restriction[];
  tags: Tag[];
}>();

const updateForm = useForm({
  name: props.image.name,
  tags: props.image.tags?.map((t) => t.id) || [],
});

// onMounted(() => {
//   console.log(props.restrictions);
// });
</script>

<template>
  <AdminLayout>
    <v-card :title="image.name">
      <v-img :src="image.url" max-width="100%" max-height="700px"></v-img>
      <v-card-text>
        <h6 class="text-h6 mb-4">Image info</h6>
        <v-form>
          <v-text-field label="Name" v-model="updateForm.name"></v-text-field>
          <v-autocomplete
            label="tags"
            v-model="updateForm.tags"
            :items="tags"
            item-title="name"
            item-value="id"
            multiple
            chips
          ></v-autocomplete>
        </v-form>
        <h6 class="text-h6 mb-4">Image Restrictions</h6>
        <v-form>
          <v-autocomplete
            label="tags"
            v-model="updateForm.tags"
            :items="tags"
            item-title="name"
            item-value="id"
            multiple
            chips
          ></v-autocomplete>
        </v-form>
      </v-card-text>
    </v-card>
  </AdminLayout>
</template>
