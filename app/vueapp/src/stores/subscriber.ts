import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
const auth = useAuthStore()
const token = auth.token

axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
