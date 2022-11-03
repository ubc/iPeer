<script lang="ts" setup>
import { toRef } from 'vue';
import { useField } from 'vee-validate';

const props = defineProps<{
  name: string,
  value: string,
  modelValue: string|number
}>();

const name = toRef(props, 'name');
const { checked, handleChange } = useField(name, undefined, {
  type: 'hidden',
  checkedValue: props.value,
});
</script>

<template>
  <input type="hidden" :name="name" :value="props.value" :checked="checked" />
</template>
