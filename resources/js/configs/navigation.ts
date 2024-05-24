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
    title: 'Manage Your Images',
    icon: 'mdi-database',
    to: 'images.manage',
    requireAuth: true,
  },
  {
    title: 'Upload a new Image',
    icon: 'mdi-upload',
    to: 'images.upload.index',
    requireAuth: true,
  },
];

export const adminNav: NavItem[] = [
  {
    title: 'Manage Images',
    to: 'admin.images.index',
    icon: 'mdi-image-multiple',
  },
  {
    title: 'Manage Users',
    to: 'admin.users.index',
    icon: 'mdi-account-group',
  },
  {
    title: 'View Flags',
    to: 'admin.flags.index',
    icon: 'mdi-flag',
  },
];
