<script lang="ts" setup>
import { useDark, useToggle } from '@vueuse/core';
import { mergeProps } from 'vue';
import { useTheme } from 'vuetify';

const theme = useTheme();

const isDark = useDark({
  onChanged(dark: boolean) {
    console.log(dark);
    theme.global.name.value = dark ? 'dark' : 'light';
  },
});

const toggleDark = useToggle(isDark);

function handleToggle() {
  toggleDark();
}

const activatorProps = computed(() => mergeProps({ onClick: handleToggle }));

const icon = computed(() =>
  isDark.value ? 'mdi-weather-night' : 'mdi-weather-sunny',
);
</script>

<template>
  <slot name="activator" :props="activatorProps" :isDark="isDark" :icon="icon">
    <v-switch
      v-model="isDark"
      hideDetails
      :true-icon="icon"
      :false-icon="icon"
      inset
    />
  </slot>
</template>
