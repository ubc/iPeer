import { ref, reactive } from 'vue'
// import {sleep} from "@/helpers";
//
// export default function useLoader() {
//   // 'READY', 'PENDING', 'LOADED', 'ERROR'
//   const loading = ref<string | null>(null)
//
//   async function setLoading(str: string | null) {
//     loading.value = 'PENDING'
//     await sleep(1000)
//     loading.value = str
//   }
//
//   return {
//     loading,
//     setLoading
//   }
// }
