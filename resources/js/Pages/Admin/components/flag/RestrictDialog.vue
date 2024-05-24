<template>
  <v-dialog max-width="400">
    <template #activator="{ props }">
      <v-btn
        variant="flat"
        v-bind="props"
        color="warning"
        prepend-icon="mdi-cancel"
      >
        Restrict Item
      </v-btn>
    </template>
    <v-card title="Restrict Item"></v-card>
  </v-dialog>
</template>
