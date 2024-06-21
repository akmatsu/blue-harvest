import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import AutoImport from 'unplugin-auto-import/vite';
import { defineConfig } from 'vite';
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
  optimizeDeps: {
    exclude: ['vuetify'],
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
      // styles: 'sass',
    }),

    AutoImport({
      imports: ['vue', 'pinia'],
      vueTemplate: true,
      dts: './resources/js/auto-imports.d.ts',
    }),
  ],
});
