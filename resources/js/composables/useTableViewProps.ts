import { Image, Paginated } from '@/types';
import { imageDelete } from '@/utils';
import { router } from '@inertiajs/vue3';

export function useTableViewProps(
  perPage: number,
  currentPage: number,
  path: string,
) {
  const selected = ref<number[]>([]);

  const itemsPerPage = ref(perPage);
  const page = ref(currentPage);

  const search = ref<string>();

  function handleDelete(id?: number | number[], admin?: boolean) {
    if (id) imageDelete(id, admin);
  }

  function handleSearch(query = search.value) {
    return router.get(
      path,
      {
        query,
        count: itemsPerPage.value,
        ...(!query && { page: page.value }),
      },
      { only: ['images'] },
    );
  }

  return {
    selected,
    itemsPerPage,
    page,
    search,
    handleDelete,
    handleSearch,
  };
}
