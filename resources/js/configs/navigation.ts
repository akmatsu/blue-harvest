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
    to: 'index',
  },
  {
    title: 'Your Images',
    icon: 'mdi-database',
    to: 'images.manage',
    requireAuth: true,
  },
  {
    title: 'Upload',
    icon: 'mdi-upload',
    to: 'upload.index',
    requireAuth: true,
  },
];

export const adminNav: NavItem[] = [
  {
    title: 'Images',
    to: 'admin.images.index',
    icon: 'mdi-image-multiple',
  },
  {
    title: 'Users',
    to: 'admin.users.index',
    icon: 'mdi-account-group',
  },
  {
    title: 'Flags',
    to: 'admin.flags.index',
    icon: 'mdi-flag',
  },
];
