<script setup  lang="ts">
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import {IconNumberOne, IconNumberThree, IconNumberTwo, IconThinkingFace} from '@/components/icons'

// DATA
const section = {
  subtitle: {
    name: 'Consider these points',
    description: 'Being asked to review peers can naturally cause discomfort for some people. It can help to remember a few points.',
    icon: {src: IconThinkingFace, size: '3.75rem'},
    points: [
      {
        id: 1,
        icon: IconNumberOne,
        title: 'You have expertise to share',
        description: 'Only you know what it felt for you to work as part of this team. You don\'t need to be an expert in evaluating teamwork to share what you experienced.'
      },
      {
        id: 2,
        icon: IconNumberTwo,
        title: 'Honesty benefits everyone.',
        description: 'Sharing your experience is good practice expressing your point-of-view. Your input helps your instructor understand what happened in the team and can help teammates see how their actions—good and bad—affect others.'
      },
      {
        id: 3,
        icon: IconNumberThree,
        title: 'Everyone will get to speak.',
        description: 'Your input alone won\'t shape your instructor\'s understanding of the team. Each of your teammates will also share their experiences. Your instructor will hear from everyone before making any grading decisions'
      }
    ]
  }
}
</script>

<template>
  <SectionSubtitle :subtitle="section.subtitle.name" :icon="section.subtitle.icon">
    <p class="text-sm leading-relaxed text-slate-900 font-normal mx-2" v-html="section.subtitle.description"></p>
    <ul class="grid grid-cols-1 gap-x-1 gap-y-2 md:gap-x-6 md:gap-y-10 md:grid-cols-3 my-4 mx-2">
      <li class="relative flex flex-row-reverse" v-for="point of section?.subtitle?.points" :key="point.id">
        <div class="peer group ml-2 mt-2 flex-auto">
          <h4 class="text-xl leading-5 xl:leading-relaxed text-slate-900 font-normal tracking-wide font-serif mb-2">{{ point.title }}</h4>
          <p class="text-sm leading-5 text-slate-900 font-normal" v-html="point.description"></p>
        </div>
        <div class="flex-none w-14 h-14 bg-white flex items-center justify-center overflow-hidden">
          <component :is="point.icon"></component>
        </div>
      </li>
    </ul>
  </SectionSubtitle>
</template>
