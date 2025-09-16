<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ year: Number, month: Number, events: Object });

const current = computed(() => new Date(props.year, props.month - 1, 1));
function daysInMonth(date){ return new Date(date.getFullYear(), date.getMonth()+1, 0).getDate(); }
function startWeekday(date){ return new Date(date.getFullYear(), date.getMonth(), 1).getDay(); }
function prev(){ const d = new Date(props.year, props.month - 2, 1); router.get(route('calendar'), { year: d.getFullYear(), month: d.getMonth()+1 }, { preserveState: false }); }
function next(){ const d = new Date(props.year, props.month, 1); router.get(route('calendar'), { year: d.getFullYear(), month: d.getMonth()+1 }, { preserveState: false }); }

function keyFor(y,m,d){ const mm = String(m).padStart(2,'0'); const dd = String(d).padStart(2,'0'); return `${y}-${mm}-${dd}`; }
</script>

<template>
  <Head title="Expiry Calendar" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-2xl text-gray-100">Certificate Expiry Calendar</h2>
        <div class="flex items-center gap-2">
          <button @click="prev" class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600">◀</button>
          <div class="text-gray-300">{{ current.toLocaleString('default', { month:'long' }) }} {{ current.getFullYear() }}</div>
          <button @click="next" class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600">▶</button>
        </div>
      </div>
    </template>

    <div class="max-w-6xl mx-auto px-4 lg:px-8 py-8 space-y-6">
      <div class="grid grid-cols-7 gap-2 text-center text-gray-400">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
      </div>
      <div class="grid grid-cols-7 gap-2">
        <template v-for="n in startWeekday(current)"><div class="h-28"></div></template>
        <div v-for="d in daysInMonth(current)" :key="d" class="relative group h-28 rounded-xl border border-gray-700 bg-gray-800/60 p-2 hover:border-gray-500 transition">
          <div class="text-sm text-gray-400">{{ d }}</div>
          <div class="mt-1 space-y-1 overflow-hidden">
            <template v-for="e in events[keyFor(current.getFullYear(), current.getMonth()+1, d)] || []" :key="e.id">
              <Link :href="route('crew.show', e.crew_id)" class="block text-xs truncate px-2 py-1 rounded-md border transition"
                :class="{
                  'bg-yellow-900/40 text-yellow-300 border-yellow-700': e.status==='expiring',
                  'bg-red-900/40 text-red-300 border-red-700': e.status==='expired',
                  'bg-gray-700 text-gray-200 border-gray-600': e.status==='valid'
                }"
                :title="`${e.crew_name} — ${e.type}`">
                {{ e.crew_name }} — {{ e.type }}
              </Link>
            </template>
          </div>

          <!-- Hover card -->
          <div v-if="(events[keyFor(current.getFullYear(), current.getMonth()+1, d)] || []).length"
               class="pointer-events-none absolute hidden group-hover:block z-10 left-0 right-0 -bottom-2 translate-y-full bg-gray-900 border border-gray-700 rounded-lg shadow p-3">
            <div class="space-y-2 max-h-48 overflow-auto">
              <div v-for="e in events[keyFor(current.getFullYear(), current.getMonth()+1, d)]" :key="e.id" class="text-sm">
                <div class="text-gray-200">{{ e.crew_name }}</div>
                <div class="text-gray-400 text-xs">{{ e.type }} • <span :class="{'text-red-400': e.status==='expired','text-yellow-300': e.status==='expiring'}">{{ e.status }}</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

