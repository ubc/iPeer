<script lang="ts" setup>
import { toRefs } from 'vue';
import { useField } from 'vee-validate';

const props = defineProps<{
  name: string,
  value: string,
}>();

const { name } = toRefs(props);
const { checked, handleChange } = useField(name, undefined, {
  type: 'hidden',
  checkedValue: props.value,
});
</script>

<template>
  <input type="hidden" :name="name" :value="value" />
</template>
