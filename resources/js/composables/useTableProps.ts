import { defineModel, defineEmits } from 'vue';

export function useTableProps() {
  return {
    selected,
    page,
    itemsPerPage,
    emits,
  };
}
