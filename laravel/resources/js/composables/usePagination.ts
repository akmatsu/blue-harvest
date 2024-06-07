import { Paginated } from '@/types';

export function usePagination(p: Paginated) {
  return {
    itemsPerPage: toRef(p.per_page || 25),
    page: toRef(p.current_page || 1),
  };
}
