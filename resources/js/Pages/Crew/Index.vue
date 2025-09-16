<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const props = defineProps({ crews: Object, filters: Object });
const search = ref(props.filters?.search || '');
function doSearch(){
  router.get(route('crew.index'), { search: search.value }, { preserveState: true, replace: true });
}
watch(search, () => doSearch());
</script>

<template>
  <Head title="Crew" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-100">Crew</h2>
    </template>
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 space-y-6">
      <div class="flex items-center gap-3">
        <input v-model="search" @input="doSearch" placeholder="Search crew..."
               class="w-full md:w-80 bg-gray-900 text-gray-200 border border-gray-700 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-500" />
        <Link href="/dashboard" class="hidden" />
      </div>

      <div class="overflow-hidden rounded-xl border border-gray-700 bg-gray-800/60">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-gray-800/80">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Crew Code</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Name</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Rank</th>
              <th class="px-4 py-3" />
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <tr v-for="c in crews.data" :key="c.id" class="hover:bg-gray-700/30 transition">
              <td class="px-4 py-3 text-gray-300">{{ c.crew_code }}</td>
              <td class="px-4 py-3 text-gray-200">{{ c.full_name }}</td>
              <td class="px-4 py-3 text-gray-400">{{ c.rank || '—' }}</td>
              <td class="px-4 py-3 text-right">
                <Link :href="route('crew.show', c.id)" class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600 transition">View</Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
  
</template>
