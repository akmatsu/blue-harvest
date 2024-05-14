<script lang="ts" setup>
const files = defineModel<File[]>();
const previews = ref<string[]>([]);

watch(
  files,
  (value) => {
    previews.value = [];
    value?.forEach((file) => {
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          if (typeof e.target?.result === 'string') {
            previews.value.push(e.target.result);
          }
        };
        reader.readAsDataURL(file);
      }
    });
  },
  {
    deep: true,
    immediate: true,
  },
);

function removeFile(index: number) {
  files.value?.splice(index, 1);
}
</script>

<template>
  <div>
    <v-file-input
      v-model="files"
      density="comfortable"
      clearable
      multiple
      chips
      accept="image/png,image/jpeg,image/jpg,image/webp"
    ></v-file-input>
    <v-row>
      <v-col v-for="(preview, index) in previews" cols="3">
        <v-card>
          <v-img :src="preview">
            <v-card-actions>
              <v-btn
                icon="mdi-close"
                variant="elevated"
                size="x-small"
                @click="removeFile(index)"
              ></v-btn>
            </v-card-actions>
          </v-img>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<style lang="scss" scoped>
.dropzone {
  border: 2px dashed #ccc;
  border-radius: 4px;
  height: 200px;
  text-align: center;
  transition: background-color 0.3s ease;
}

.dropzone-active {
  background-color: #e0e0e0;
}
</style>
