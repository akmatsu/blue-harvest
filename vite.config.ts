import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';

export default defineConfig({
  optimizeDeps: {
    include: ['vuetify'],
  },
  plugins: [
    laravel({
      input: 'resources/js/app.ts',
      ssr: 'resources/js/ssr.ts',
      refresh: true,
    }),

    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),

    vuetify({
      autoImport: true,
      styles: { configFile: '/resources/css/vuetifySettings.scss' },
    }),

    AutoImport({
      imports: ['vue'],
      vueTemplate: true,
      dts: './resources/js/auto-imports.d.ts',
    }),

    Components({
      dirs: ['./resources/js/Components', './resources/js/Layouts'],
      extensions: ['vue'],
      dts: './resources/js/components.d.ts',
      include: [/\.vue%/, /\.vue\?vue/],
    }),
  ],
});
