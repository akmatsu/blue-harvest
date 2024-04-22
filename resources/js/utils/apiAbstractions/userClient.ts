import { apiV1Instance } from './apiInstance';

const api = apiV1Instance;

export function getUser() {
  return api.get('/user');
}
