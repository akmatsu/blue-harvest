import axios from 'axios';

export const apiV1Instance = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
});

export const webInstance = axios.create({
  baseURL: 'http://localhost:8000',
});
