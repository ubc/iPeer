import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
  return {
    plugins: [vue()],
    build: {
      emptyOutDir: false,
      outDir: '../vue/',
      manifest: true,
      rollupOptions: {
        input: {
          main: './src/main.ts'
        },
        output: {
          entryFileNames: '',
          chunkFileNames: '',
          assetFileNames: ''
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