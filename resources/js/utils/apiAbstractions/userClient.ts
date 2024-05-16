import axios from 'axios';
import { apiV1Instance } from './apiInstance';
import { router } from '@inertiajs/vue3';
import { useToasts } from '@/store/toasts';

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

export function imageDelete(id: number | number[]) {
  const toast = useToasts();
  if (Array.isArray(id)) {
    router.delete('/images', {
      data: {
        ids: id,
      },
      onSuccess: () => toast.success('Successfully deleted images.'),
      onError: (err) => toast.error(err.message),
    });
  } else {
    router.delete(`/images/${id}`, {
      onSuccess: () => toast.success('Successfully deleted image.'),
      onError: (err) => toast.error(err.message),
    });
  }
}
