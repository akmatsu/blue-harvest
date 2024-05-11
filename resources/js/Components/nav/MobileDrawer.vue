<script lang="ts" setup>
import { NavigationMenu } from '.';
import { useDisplay } from 'vuetify';
const show = defineModel({ default: false });

const { mdAndUp } = useDisplay();
</script>

<template>
  <v-navigation-drawer v-if="!mdAndUp" v-model="show" location="top" mobile>
    <NavigationMenu />
  </v-navigation-drawer>
</template>
