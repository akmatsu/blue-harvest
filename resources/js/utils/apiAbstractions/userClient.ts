import { useToasts } from '@/store/toasts';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { apiV1Instance } from './apiInstance';

const api = apiV1Instance;

export function getOptimizedImage() {
  return api.get('/blue-harvest', {
    params: {
      input: 'https://images.unsplash.com/photo-1504593811423-6dd665756598',
    },
  });
}

export function uploadImages(images: File[]) {
  const formData = new FormData();

  images.forEach((file) => formData.append('files[]', file));
  return axios.post('/images', formData);
}

export function imageDelete(id: number | number[], admin?: boolean): void {
  const toast = useToasts();
  if (Array.isArray(id)) {
    const routeName = admin ? 'admin.images.delete.bulk' : 'images.delete.bulk';
    router.delete(route(routeName), {
      data: {
        ids: id,
      },
      onSuccess: () => toast.success('Successfully deleted images.'),
      onError: (err) => toast.error(err.message),
      preserveState: false,
    });
  } else {
    const routeName = admin ? 'admin.images.delete' : 'images.delete';
    router.delete(route(routeName, { id }), {
      onSuccess: () => toast.success('Successfully deleted image.'),
      onError: (err) => toast.error(err.message),
      preserveState: false,
    });
  }
}
