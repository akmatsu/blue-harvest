import axios from 'axios';
import { apiV1Instance, webInstance } from './apiInstance';

const api = apiV1Instance;
const webApi = webInstance;

export function getOptimizedImage() {
  return api.get('/blue-harvest', {
    params: {
      input: 'https://images.unsplash.com/photo-1504593811423-6dd665756598',
    },
  });
}

export function uploadImages(images: FileList) {
  const formData = new FormData();
  const files = Array.from(images);

  files.forEach((file) => formData.append('files[]', file));
  return axios.post('/images', formData);
}

export function imageDelete(id: number | number[]) {
  if (Array.isArray(id)) {
    return axios.delete('/images', { data: { ids: id } });
  } else {
    return axios.delete(`/images/${id}`);
  }
}
