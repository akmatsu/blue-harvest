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

export function imageDelete(id: number | number[]) {
  if (Array.isArray(id)) {
    return axios.delete('/images', { data: { ids: id } });
  } else {
    return axios.delete(`/images/${id}`);
  }
}
