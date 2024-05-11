export const navigation = {
  items: [
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
  ],
};
