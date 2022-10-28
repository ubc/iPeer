import { fileURLToPath, URL } from 'node:url'

import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ command, mode }) => {
  // Load env file based on `mode` in the current working directory.
  // Set the third parameter to '' to load all env regardless of the `VITE_` prefix.
  const env = loadEnv(mode, process.cwd(), '')
  console.log('APP_ENV',env.APP_ENV)
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
      // host: true,
      host: env.APP_ENV,
    },
  }
})
// add to custom tags