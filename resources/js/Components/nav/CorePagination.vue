<script lang="ts" setup>
import { Paginated } from '@/types';
import PaginationBtn from './PaginationBtn.vue';

const props = withDefaults(
  defineProps<{
    pagination: Paginated;
    search?: string;
    totalVisible?: number | string;
  }>(),
  {
    totalVisible: 6,
  },
);

const currentPage = ref(props.pagination.current_page);

const totalPages = computed(() =>
  Math.ceil(props.pagination.total / props.pagination.per_page),
);

const currentRoute = computed(() => route().current());
</script>

<template>
  <v-pagination
    v-model="currentPage"
    :length="totalPages"
    density="compact"
    size="small"
    :total-visible="totalVisible"
  >
    <template #item="{ isActive, page, props }">
      <PaginationBtn
        v-bind="props"
        :link="currentRoute"
        :params="{ page, query: search }"
        :is-active="isActive"
      >
        {{ page }}
      </PaginationBtn>
    </template>
    <template
      #first="{
        disabled,
        icon,
        'aria-label': ariaLabel,
        'aria-disabled': ariaDisabled,
      }"
    >
      <PaginationBtn
        :icon="icon"
        :link="currentRoute"
        :params="{ page: 1, query: search }"
        :disabled="disabled"
        :aria-disabled="ariaDisabled"
        :aria-label="ariaLabel"
      >
        <v-icon :icon="icon" />
      </PaginationBtn>
    </template>
    <template
      #last="{
        disabled,
        icon,
        'aria-label': ariaLabel,
        'aria-disabled': ariaDisabled,
      }"
    >
      <PaginationBtn
        :icon="icon"
        :link="currentRoute"
        :params="{ page: pagination.last_page, query: search }"
        :disabled="disabled"
        :aria-disabled="ariaDisabled"
        :aria-label="ariaLabel"
      >
        <v-icon :icon="icon" />
      </PaginationBtn>
    </template>
    <template
      #next="{
        disabled,
        icon,
        'aria-label': ariaLabel,
        'aria-disabled': ariaDisabled,
      }"
    >
      <PaginationBtn
        :icon="icon"
        :link="currentRoute"
        :params="{ page: currentPage + 1, query: search }"
        :disabled="disabled"
        :aria-disabled="ariaDisabled"
        :aria-label="ariaLabel"
      >
        <v-icon :icon="icon" />
      </PaginationBtn>
    </template>
    <template
      #prev="{
        disabled,
        icon,
        'aria-label': ariaLabel,
        'aria-disabled': ariaDisabled,
      }"
    >
      <PaginationBtn
        :icon="icon"
        :link="currentRoute"
        :params="{ page: currentPage - 1, query: search }"
        :disabled="disabled"
        :aria-disabled="ariaDisabled"
        :aria-label="ariaLabel"
      >
        <v-icon :icon="icon" />
      </PaginationBtn>
    </template>
  </v-pagination>
</template>
