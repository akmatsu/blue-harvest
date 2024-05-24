import { Config } from 'ziggy-js';

export interface Role {
  id: number;
  name: string;
  guard_name: string;
  created_at: string;
  updated_at: string;
  pivot: {
    model_type: string;
    model_id: number;
    role_id: number;
  };
}

export interface Flag {
  id: number;
  created_at: string;
  updated_at: string;
  user_id?: number | null;
  flaggable_type: string;
  flaggable_id: number;
  reason: string;
  flaggable?: Image;
}

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string | null;
  created_at: string;
  updated_at: string;
  permissions?: [];
  roles?: Role[];
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
  optimized_images?: OptimizedImage[];
  tags?: Tag[];
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

export interface Tag {
  id: number;
  name: string;
  description: string;
  created_at: string;
  updated_at: string;
}

export interface Paginated<T = any> {
  current_page: 1;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: {
    url?: string | null;
    label: string;
    active?: boolean;
  }[];
  next_page_url: string;
  path: string;
  per_page: number;
  prev_page_url?: string | null;
  to: number;
  total: number;
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: User;
  };
  ziggy: Config & { location: string };
};

export type PopularSearches = {
  document: {
    count: number;
    id: string;
    query: string;
    timestamp: number;
  };
  highlight: [];
  highlights: [];
};

export interface CoreLinkProps {
  link?: string;
  params?: {
    [key: string]: string | number | undefined;
  };
  preserveScroll?: boolean;
  preserveState?: boolean;
  only?: string[];
}
