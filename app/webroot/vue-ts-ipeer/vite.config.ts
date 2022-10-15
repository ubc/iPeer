import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
  return {
    plugins: [vue()],
    build: {
      outDir: '../vue/webroot',
      sourcemap: true,
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: {
          main: './src/main.ts'
        },
        output: {
          entryFileNames: 'js/vue/[name].js', // default '[name].[hash].js',
          chunkFileNames: 'js/vue/[name].js', // default '[name].[hash].js',
          assetFileNames: 'css/vue/[name].[ext]', // default '[name].[hash].[ext]'
        }
      }
    },
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url)),
        'envConfig': fileURLToPath(new URL(`./src/config/${mode}.ts`, import.meta.url)),
      }
    },
    server: {
      host: true,
    },
  }
})
// add to custom tags