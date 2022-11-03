import { fileURLToPath, URL } from 'node:url'

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ command, mode }) => {
  // Load env file based on `mode` in the current working directory.
  // Set the third parameter to '' to load all env regardless of the `VITE_` prefix.
  const env = loadEnv(mode, process.cwd(), '')
  const development = env.APP_ENV === 'development' ? true : false
  return {
    plugins: [vue()],
    build: {
      outDir: '../webroot/',
      sourcemap: development,
      emptyOutDir: false,
      manifest: true,
      rollupOptions: {
        input: {
          main: './src/main.ts'
        },
        output: {
          entryFileNames: development ? 'js/vueapp/[name].js' : 'js/vueapp/[name].[hash].js',
          chunkFileNames: development ? 'js/vueapp/[name].js' : 'js/vueapp/[name].[hash].js',
          assetFileNames: development ? 'css/vueapp/[name].[ext]' : 'css/vueapp/[name].[hash].[ext]'
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
      // host: true, // env.APP_ENV,
    },
  }
})
// add to custom tags