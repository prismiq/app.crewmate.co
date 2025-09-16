<script setup>
import { computed } from 'vue';
const props = defineProps({ status: String });
const color = computed(() => ({
  valid: 'bg-green-900/60 text-green-300 border-green-700',
  expiring: 'bg-yellow-900/40 text-yellow-300 border-yellow-700',
  expired: 'bg-red-900/40 text-red-300 border-red-700',
  flagged: 'bg-orange-900/40 text-orange-300 border-orange-700',
}[props.status] || 'bg-gray-800 text-gray-300 border-gray-700'));
</script>

<template>
  <span :class="['inline-flex items-center px-3 py-1 rounded-full border text-xs font-medium transition', color]">
    {{ (status||'valid').charAt(0).toUpperCase()+ (status||'valid').slice(1) }}
  </span>
</template>
