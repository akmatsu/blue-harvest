import 'vuetify/styles';

import { createVuetify } from 'vuetify';
import { VBtn } from 'vuetify/components';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import { VSnackbarQueue } from 'vuetify/labs/VSnackbarQueue';

export const vuetify = createVuetify({
  ssr: true,
  aliases: {
    PrimaryBtn: VBtn,
    SecondaryBtn: VBtn,
  },
  defaults: {
    global: {},
    VAppBar: {
      flat: true,
      border: 'b',
    },
    PrimaryBtn: {
      flat: true,
      slim: true,
      color: 'primary',
    },
    SecondaryBtn: {
      flat: true,
      slim: true,
      variant: 'plain',
    },
    VBtn: {
      flat: true,
      slim: true,
    },
    VTextField: {
      color: 'primary',
      variant: 'outlined',
      density: 'compact',
    },
    VCombobox: {
      color: 'primary',
      variant: 'outlined',
      density: 'compact',
    },
    VAutocomplete: {
      color: 'primary',
      variant: 'outlined',
      density: 'compact',
    },
    VList: {
      density: 'comfortable',
      nav: true,
    },

    VListItemTitle: {
      class: 'text-capitalize text-body-2',
    },
    VListItemSubtitle: {
      class: 'text-caption',
    },
    VFileInput: {
      variant: 'outlined',
      color: 'primary',
      density: 'compact',
    },
    VSelect: {
      color: 'primary',
      variant: 'outlined',
      density: 'compact',
    },
    VToolbar: {
      color: 'surface',
      density: 'compact',
    },
  },
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        dark: false,
        colors: {
          background: '#FAFAFA',
          surface: '#FFFFFF',
          // text: '#424242',
          // primary: '#7C4DFF',
          // secondary: '#18FFFF',
          // success: '#26A69A',
          // error: '#FF1744',
          // warning: '#FFAB00',
          // info: '#00E5FF',
        },
      },
      dark: {
        dark: true,
        colors: {
          background: '#0F0F0F',
          surface: '#252525',
          // text: '#DADADA',
          // primary: '#FF4081',
          // success: '#66BB6A',
          // error: '#FF5252',
          // warning: '#FFCA28',
          // info: '#29B6F6',
        },
      },
    },
  },
  icons: {
    defaultSet: 'mdi',
    aliases: {
      ...aliases,
    },
    sets: {
      mdi,
    },
  },
  components: {
    VSnackbarQueue,
  },
});
