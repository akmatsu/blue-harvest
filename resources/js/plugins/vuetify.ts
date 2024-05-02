import 'vuetify/styles';

import { createVuetify } from 'vuetify';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import { VSnackbarQueue } from 'vuetify/labs/VSnackbarQueue';

export const vuetify = createVuetify({
  ssr: true,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
  components: {
    VSnackbarQueue,
  },
});
