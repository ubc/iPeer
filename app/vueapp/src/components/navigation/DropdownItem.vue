<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

import type { IUser } from '@/types/typings'
interface Props {
  currentUser: IUser
}
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<Props>()

// DATA
const dropdown = ref(false)

// COMPUTED

// METHODS
function toggleDropdown(): void {
  dropdown.value = !dropdown.value
}

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="flex justify-center">
    <div>
      <div class="dropdown relative">
        <div class="dropdown-toggle
                      text-sm px-4 -mr-4 py-2.5 leading-relaxed tracking-wide space-x-2
                      hover:text-green-700 active:text-blue-800
                      flex items-center space-x-2 cursor-pointer
                      whitespace-nowrap transition duration-150 ease-in-out"
             id="dropdownMenu"
             data-bs-toggle="dropdown"
             aria-expanded="false"
             @click="toggleDropdown"
        >
          {{ props.currentUser?.first_name }} {{ props.currentUser?.last_name }}
          <svg @click="toggleNavDropdown" class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </div>
        <ul class="dropdown-menu absolute -right-2 w-[180px] min-w-max
                  bg-white dark:bg-gray-600
                  text-base float-left list-none text-left rounded-lg
                  shadow-lg mt-1 m-0 py-2 bg-clip-padding border-none z-50"
            :class="`${dropdown?'':'hidden'}`" aria-labelledby="dropdownMenu">
          <li>
            <router-link
                class="dropdown-item flex items-center text-sm py-2 px-4 font-normal block flex-1 whitespace-nowrap bg-transparent hover:bg-gray-100"
                :to="{ name: 'profile.edit' }" @click="toggleDropdown">
              <svg class="w-4 h-4 -ml-2 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              Edit Profile
            </router-link>
          </li>
          <li>
            <a href="" @click="authStore.logout()"
               class="dropdown-item flex items-center text-sm py-2 px-4 font-normal block flex-1 whitespace-nowrap bg-transparent hover:bg-gray-100 cursor-pointer"
            >
              <svg class="w-4 h-4 -ml-2 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
