export type NavItem = {
  title: string;
  icon?: string;
  to: string;
  requireAuth?: boolean;
  requireAdmin?: boolean;
};

export const navigation: NavItem[] = [
  {
    title: 'Browse',
    icon: 'mdi-view-dashboard',
    to: '/',
  },
  {
    title: 'Manage Your Images',
    icon: 'mdi-database',
    to: '/images',
    requireAuth: true,
  },
  {
    title: 'Upload a new Image',
    icon: 'mdi-upload',
    to: '/upload',
    requireAuth: true,
  },
];

export const adminNav: NavItem[] = [
  {
    title: 'Manage Images',
    to: '/admin/images',
    icon: 'mdi-image-multiple',
  },
  {
    title: 'Manage Users',
    to: '/admin/users',
    icon: 'mdi-account-group',
  },
];
