<script lang="ts" setup>
import { toRef } from 'vue';
import { IconLeftArrow, IconRightArrow } from '@/components/icons'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:paginate', option: object): void
}>()
const props = defineProps<{
  paginate: object
}>()
// DATA
const paginate = toRef(props, 'paginate')
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<!--// Displaying 1 - 5 of 5  -->
<template>
  <div class="pagination flex justify-between items-center text-sm text-slate-700 leading-relaxed mt-4">
    <div class="displaying text-slate-700">
      Displaying {{ paginate.offset+1 }}
      - {{ Number(paginate.end) > paginate.total ? paginate.total : paginate.end }}
      of {{ paginate.total }}
    </div>
    <div class="paginate text-slate-700">
      <ul class="flex">
        <li>
          <button :class="`paginate ${paginate.page === 1 ? 'disabled' : ''}`"
              :disabled="paginate.page === 1"
              @click="paginate.page--"><IconLeftArrow class="w-4 h-4" /></button>
        </li>
        <li v-for="(_, index) of paginate.pages" :key="index">
          <button :class="`paginate ${paginate.page === (index+1) ? 'active' : ''}`"
              @click="paginate.page = (index + 1)">{{ index + 1 }}</button>
        </li>
        <li>
          <button :class="`paginate ${paginate.page === paginate.pages ? 'disabled' : ''}`"
              :disabled="paginate.page === paginate.pages"
              @click="paginate.page++"><IconRightArrow class="w-4 h-4" /></button>
        </li>
      </ul>
    </div>
  </div>
</template>
