<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
const props = defineProps({ crews: Array, types: Array, vessels: Array, filters: Object });

const search = ref(props.filters?.search || '');
const crewFilter = ref(props.filters?.crew || '');
const typeFilter = ref(props.filters?.type || '');
const statusFilter = ref(props.filters?.status || '');
const vesselFilter = ref(props.filters?.vessel || '');

function refresh(){
  router.get(route('certificates.matrix'), {
    search: search.value||undefined,
    crew: crewFilter.value||undefined,
    type: typeFilter.value||undefined,
    status: statusFilter.value||undefined,
    vessel: vesselFilter.value||undefined,
  }, { preserveState: true, replace: true });
}

function exportCsv(){
  const q = new URLSearchParams({
    search: search.value||'', crew: crewFilter.value||'', type: typeFilter.value||'', status: statusFilter.value||'', vessel: vesselFilter.value||''
  });
  window.open(`${route('certificates.matrix.export')}?${q.toString()}`,'_blank');
}

function statusFor(crew, typeId){
  const cert = crew.certificates.find(c => c.certificate_type_id === typeId);
  return cert ? cert.status : 'missing';
}

// Quick add modal
import { useForm } from '@inertiajs/vue3';
const quick = ref({ open:false, crew:null, type:null });
const qform = useForm({
  crew_id: '',
  certificate_type_id: '',
  vessel_id: '',
  issue_date: '',
  expiry_date: '',
  file: null,
});
function openQuick(crew, type){
  quick.value = { open:true, crew, type };
  qform.reset();
  qform.crew_id = crew.id;
  qform.certificate_type_id = type.id;
}
function saveQuick(){
  const toIso = (v)=>{ if (!v) return v; if (String(v).includes('/')) { const [dd,mm,yy]=String(v).split('/'); return `${yy}-${mm}-${dd}`; } return v; };
  qform.transform((d)=>({ ...d, issue_date: toIso(d.issue_date), expiry_date: toIso(d.expiry_date) }))
    .post(route('certificates.store'), { forceFormData: true, onSuccess: () => { quick.value.open=false; router.reload({ only:['crews'] }); } });
}
</script>

<template>
  <Head title="Certificate Matrix" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-100">Crew Certificates Matrix</h2>
    </template>
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 space-y-4">
      <div>
        <input v-model="search" @input="refresh" placeholder="Search crew or certificate" class="w-full md:w-1/2 bg-gray-900 text-gray-200 border border-gray-700 rounded-md px-3 py-2" />
      </div>

      <div class="flex flex-wrap gap-3 items-center">
        <select v-model="crewFilter" @change="refresh" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2">
          <option value="">Filter by Crew</option>
          <option v-for="c in crews" :key="c.id" :value="c.id">{{ c.full_name }}</option>
        </select>
        <select v-model="typeFilter" @change="refresh" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2">
          <option value="">Filter by Certificate Type</option>
          <option v-for="t in types" :key="t.id" :value="t.id">{{ t.name }}</option>
        </select>
        <select v-model="statusFilter" @change="refresh" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2">
          <option value="">Filter by Expiry Status</option>
          <option value="valid">Valid</option>
          <option value="expiring">Expiring</option>
          <option value="expired">Expired</option>
          <option value="missing">Missing</option>
        </select>
        <select v-model="vesselFilter" @change="refresh" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2">
          <option value="">Filter by Vessel</option>
          <option v-for="v in vessels" :key="v.id" :value="v.id">{{ v.name }}</option>
        </select>

        <button @click="exportCsv" class="ml-auto px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100 transition">Export</button>
      </div>
      <div class="overflow-auto rounded-xl border border-gray-700 bg-gray-800/60">
        <table class="min-w-full">
          <thead class="bg-gray-800/80">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Crew Member</th>
              <th v-for="t in types" :key="t.id" class="px-4 py-3 text-left text-xs font-semibold text-gray-300 whitespace-nowrap">{{ t.name }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <tr v-for="c in crews" :key="c.id" class="hover:bg-gray-700/30 transition">
              <td class="px-4 py-3 text-gray-200 whitespace-nowrap">{{ c.full_name }}</td>
              <td v-for="t in types" :key="t.id" class="px-4 py-3">
                <button @click="openQuick(c, t)" class="focus:outline-none">
                  <StatusBadge :status="statusFor(c, t.id)" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Quick Add Modal -->
      <div v-if="quick.open" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-700 rounded-xl w-full max-w-lg p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-gray-200 text-lg font-semibold">Add Certificate</h3>
            <button @click="quick.open=false" class="text-gray-400 hover:text-gray-200">✕</button>
          </div>
          <p class="text-gray-400 text-sm">{{ quick.crew?.full_name }} – {{ quick.type?.name }}</p>
          <div class="grid grid-cols-2 gap-3">
            <select v-model="qform.vessel_id" class="col-span-2 bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2">
              <option value="">No Vessel</option>
              <option v-for="v in vessels" :key="v.id" :value="v.id">{{ v.name }}</option>
            </select>
            <input v-model="qform.issue_date" placeholder="DD/MM/YYYY" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
            <input v-model="qform.expiry_date" placeholder="DD/MM/YYYY" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
            <input type="file" @change="e=>qform.file=e.target.files[0]" class="col-span-2 text-gray-300" />
          </div>
          <div class="flex justify-end gap-2">
            <button @click="quick.open=false" class="px-4 py-2 rounded-md bg-gray-700 text-gray-100">Cancel</button>
            <button @click="saveQuick" :disabled="qform.processing" class="px-4 py-2 rounded-md bg-gray-600 hover:bg-gray-500 text-gray-100 disabled:opacity-50">Save</button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
