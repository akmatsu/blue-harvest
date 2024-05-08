import { Config } from 'ziggy-js';

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
}

export interface Image {
  created_at: string;
  id: number;
  mime_type: string | null;
  name: string;
  path: string;
  size: number;
  updated_at: string;
  user_id: number;
  url: string;
  width: number;
  height: number;
  optimizedImages: {
    small?: OptimizedImage;
    medium?: OptimizedImage;
    large?: OptimizedImage;
  };
}

export interface OptimizedImage {
  id: number;
  created_at: string;
  updated_at: string;
  image_id: number;
  file_size: number;
  path: string;
  url: string;
  size: string;
  width: number;
  height: number;
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User;
  };
  ziggy: Config & { location: string };
};
