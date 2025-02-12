import { Image } from '@/types';
import { defineModel } from 'vue';

export function useTableProps() {
  const selected = defineModel<number[]>();
  const page = defineModel<number | string>('page');
  const itemsPerPage = defineModel<string | number>('itemsPerPage', {
    default: 25,
  });

  function getItemUrl(image: Image) {
    if (image.optimized_images) {
      const img = image.optimized_images.find((im) => im.size === 'small');
      if (img) return img?.url;
    }
    return image.url;
  }

  return {
    selected,
    page,
    itemsPerPage,
    getItemUrl,
  };
}
