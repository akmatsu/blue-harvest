import 'vuetify/styles';

import { createVuetify } from 'vuetify';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import { VSnackbarQueue } from 'vuetify/labs/VSnackbarQueue';

export const vuetify = createVuetify({
  ssr: true,
  defaults: {
    global: {},
    VAppBar: {
      flat: true,
      border: 'b',
      color: 'primary',
    },
    VBtn: {
      flat: true,
    },
    VTextField: {
      color: 'primary',
      variant: 'outlined',
    },
    VAutocomplete: {
      color: 'primary',
      variant: 'outlined',
    },
    VSelect: {
      color: 'primary',
      variant: 'outlined',
    },
    VToolbar: {
      color: 'surface',
    },
  },
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        dark: false,
        colors: {
          background: '#f1f1f1',
          primary: '#2b5fb3',
          // surface: '',
        },
      },
    },
  },
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
