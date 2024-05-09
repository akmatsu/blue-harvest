<script lang="ts" setup>
const show = ref(false);
const icon = computed(() => (show.value ? 'mdi-eye-off' : 'mdi-eye'));
const type = computed(() => (show.value ? 'text' : 'password'));

const password = defineModel<string>();
</script>

<template>
  <v-text-field
    v-model="password"
    :type="type"
    :append-inner-icon="icon"
    @click:append-inner="show = !show"
  ></v-text-field>
</template>
