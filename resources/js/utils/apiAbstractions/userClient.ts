import { apiV1Instance } from './apiInstance';

const api = apiV1Instance;

export function getOptimizedImage() {
  return api.get('/blue-harvest', {
    params: {
      input: 'https://images.unsplash.com/photo-1504593811423-6dd665756598',
    },
  });
}
