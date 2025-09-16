<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ crew: Object, certificateTypes: Array, logs: Array });
const tab = ref('documents');
const showUpload = ref(false);
const fmt = (d) => {
  if (!d) return '—';
  const parts = String(d).split('-');
  if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
  try { return new Date(d).toLocaleDateString('en-GB'); } catch(e){ return d; }
};

const form = useForm({
  crew_id: props.crew.id,
  certificate_type_id: '',
  vessel_id: '',
  reference: '',
  issue_date: '',
  expiry_date: '',
  notes: '',
  file: null,
});

function submit() {
  // transform DD/MM/YYYY to YYYY-MM-DD
  form.transform((data) => {
    const toIso = (v) => {
      if (!v) return v;
      if (String(v).includes('/')) { const [dd,mm,yy] = String(v).split('/'); return `${yy}-${mm}-${dd}`; }
      return v;
    };
    return { ...data, issue_date: toIso(data.issue_date), expiry_date: toIso(data.expiry_date) };
  }).post(route('certificates.store'), { forceFormData: true, onSuccess: () => { form.reset('certificate_type_id','reference','issue_date','expiry_date','notes','file'); showUpload.value=false; router.reload({ only:['crew','logs'] }); } });
}

// Inline profile edit
const editingProfile = ref(false);
const profileForm = useForm({
  crew_code: props.crew.crew_code,
  first_name: props.crew.first_name,
  last_name: props.crew.last_name,
  email: props.crew.email,
  phone: props.crew.phone,
  rank: props.crew.rank,
  nationality: props.crew.nationality,
  date_of_birth: props.crew.date_of_birth,
  address_line1: props.crew.address_line1,
  address_line2: props.crew.address_line2,
  city: props.crew.city,
  state: props.crew.state,
  postal_code: props.crew.postal_code,
  country: props.crew.country,
});
function saveProfile(){
  profileForm.transform((data)=>{
    const toIso = (v)=>{ if (!v) return v; if (String(v).includes('/')) { const [dd,mm,yy]=String(v).split('/'); return `${yy}-${mm}-${dd}`; } return v; };
    return { ...data, date_of_birth: toIso(data.date_of_birth) };
  }).put(route('crew.update', props.crew.id), { onSuccess: ()=>{ editingProfile.value=false; router.reload({ only:['crew','logs'] }); } });
}
</script>

<template>
  <Head :title="`${crew.full_name} – Documents`" />
  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="font-semibold text-2xl text-gray-100">{{ crew.full_name }}</h2>
        <p class="text-gray-400 text-sm">Crew ID: {{ crew.crew_code }}</p>
      </div>
    </template>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 space-y-6">
      <div class="flex items-center gap-2 text-gray-300">
        <button @click="tab='profile'" :class="['px-3 py-1.5 rounded-md transition', tab==='profile' ? 'bg-gray-900 border border-gray-700' : 'bg-gray-700 hover:bg-gray-600']">Profile</button>
        <button @click="tab='documents'" :class="['px-3 py-1.5 rounded-md transition', tab==='documents' ? 'bg-gray-900 border border-gray-700' : 'bg-gray-700 hover:bg-gray-600']">Documents</button>
        <button @click="tab='history'" :class="['px-3 py-1.5 rounded-md transition', tab==='history' ? 'bg-gray-900 border border-gray-700' : 'bg-gray-700 hover:bg-gray-600']">History</button>
      </div>

      <div class="flex items-center justify-between">
        <h3 class="text-gray-200 text-xl font-semibold">Certificates</h3>
        <button @click="showUpload=true" class="px-4 py-2 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600 transition">Upload Certificate</button>
      </div>

      <div v-if="tab==='profile'" class="rounded-xl border border-gray-700 bg-gray-800/60 p-6 text-gray-300">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-gray-200 font-semibold">Profile</h4>
          <div class="space-x-2">
            <button v-if="!editingProfile" @click="editingProfile=true" class="px-3 py-1.5 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100">Edit</button>
            <template v-else>
              <button @click="editingProfile=false" class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100">Cancel</button>
              <button @click="saveProfile" :disabled="profileForm.processing" class="px-3 py-1.5 rounded-md bg-gray-600 hover:bg-gray-500 text-gray-100 disabled:opacity-50">Save</button>
            </template>
          </div>
        </div>

        <div v-if="!editingProfile" class="grid md:grid-cols-2 gap-4">
          <div>
            <div class="text-gray-500 text-xs">First Name</div>
            <div class="text-gray-200 text-sm">{{ crew.first_name }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Last Name</div>
            <div class="text-gray-200 text-sm">{{ crew.last_name }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Crew Code</div>
            <div class="text-gray-200 text-sm">{{ crew.crew_code }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Rank</div>
            <div class="text-gray-200 text-sm">{{ crew.rank || '—' }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Email</div>
            <div class="text-gray-200 text-sm">{{ crew.email || '—' }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Phone</div>
            <div class="text-gray-200 text-sm">{{ crew.phone || '—' }}</div>
          </div>
          <div class="md:col-span-2">
            <div class="text-gray-500 text-xs">Address</div>
            <div class="text-gray-200 text-sm">{{ [crew.address_line1, crew.address_line2].filter(Boolean).join(', ') || '—' }}</div>
            <div class="text-gray-400 text-xs">{{ [crew.city, crew.state, crew.postal_code, crew.country].filter(Boolean).join(', ') }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Nationality</div>
            <div class="text-gray-200 text-sm">{{ crew.nationality || '—' }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-xs">Date of Birth</div>
            <div class="text-gray-200 text-sm">{{ fmt(crew.date_of_birth) }}</div>
          </div>
        </div>

        <div v-else class="grid md:grid-cols-2 gap-3">
          <input v-model="profileForm.first_name" placeholder="First Name" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.last_name" placeholder="Last Name" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.crew_code" placeholder="Crew Code" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.rank" placeholder="Rank" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.email" placeholder="Email" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.phone" placeholder="Phone" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.address_line1" placeholder="Address line 1" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 md:col-span-2" />
          <input v-model="profileForm.address_line2" placeholder="Address line 2" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 md:col-span-2" />
          <input v-model="profileForm.city" placeholder="City" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.state" placeholder="State/Region" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.postal_code" placeholder="Postal Code" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <input v-model="profileForm.country" placeholder="Country" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          <div class="md:col-span-2">
            <label class="block text-gray-300 text-sm mb-1">Date of Birth <span class="text-gray-500 text-xs">(DD/MM/YYYY)</span></label>
            <input v-model="profileForm.date_of_birth" placeholder="DD/MM/YYYY" class="w-full bg-gray-900 border border-gray-700 rounded-md px-3 py-2 text-gray-200" />
          </div>
        </div>
      </div>

      <div v-if="tab==='documents'" class="overflow-hidden rounded-xl border border-gray-700 bg-gray-800/60">
        <table class="min-w-full divide-y divide-gray-700">
          <thead>
          <tr class="bg-gray-800/80">
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Certificate</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Issue Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Expiry Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Status</th>
            <th class="px-4 py-3" />
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
          <tr v-for="cert in crew.certificates" :key="cert.id" class="hover:bg-gray-700/30 transition">
            <td class="px-4 py-3 text-gray-200">{{ cert.type?.name }}</td>
            <td class="px-4 py-3 text-gray-400">{{ fmt(cert.issue_date) }}</td>
            <td class="px-4 py-3 text-gray-400">{{ fmt(cert.expiry_date) }}</td>
            <td class="px-4 py-3"><StatusBadge :status="cert.status" /></td>
            <td class="px-4 py-3 text-right">
              <button class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600 transition">View</button>
            </td>
          </tr>
          <tr v-if="crew.certificates.length === 0">
            <td colspan="5" class="px-4 py-8 text-center text-gray-400">No certificates uploaded yet.</td>
          </tr>
          </tbody>
        </table>
      </div>

      <div v-if="tab==='history'" class="overflow-hidden rounded-xl border border-gray-700 bg-gray-800/60">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-gray-800/80">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">When</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Action</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Details</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <tr v-for="l in logs" :key="l.id">
              <td class="px-4 py-3 text-gray-400">{{ new Date(l.created_at).toLocaleString('en-GB') }}</td>
              <td class="px-4 py-3 text-gray-200">{{ l.action.replace('_', ' ') }}</td>
              <td class="px-4 py-3 text-gray-400 text-sm">
                <span v-if="l.action==='created'">Crew profile created.</span>
                <span v-else-if="l.action==='updated'">Profile updated.</span>
                <span v-else-if="l.action==='certificate_uploaded'">Uploaded certificate {{ l.meta?.type_id ? '#' + l.meta.type_id : '' }}.</span>
                <span v-else-if="l.action==='deleted'">Crew profile deleted.</span>
                <span v-else>—</span>
              </td>
            </tr>
            <tr v-if="!logs || logs.length===0">
              <td colspan="3" class="px-4 py-8 text-center text-gray-400">No history yet.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Upload modal -->
      <div v-if="showUpload" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4">
        <div class="bg-gray-800/95 border border-gray-700 rounded-2xl w-full max-w-xl p-6 space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <h3 class="text-gray-200 text-lg font-semibold">Upload Certificate</h3>
            <button @click="showUpload=false" class="text-gray-400 hover:text-gray-200">✕</button>
          </div>
          <form @submit.prevent="submit" class="space-y-3">
            <div>
              <label class="block text-gray-300 text-sm mb-1">Certificate Type</label>
              <select v-model="form.certificate_type_id" class="w-full bg-gray-900 border border-gray-700 rounded-md px-3 py-2 text-gray-200">
                <option value="" disabled>Select type</option>
                <option v-for="t in certificateTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-gray-300 text-sm mb-1">Issue Date <span class="text-gray-500 text-xs">(DD/MM/YYYY)</span></label>
                <input v-model="form.issue_date" type="date" class="w-full bg-gray-900 border border-gray-700 rounded-md px-3 py-2 text-gray-200" />
              </div>
              <div>
                <label class="block text-gray-300 text-sm mb-1">Expiry Date <span class="text-gray-500 text-xs">(DD/MM/YYYY)</span></label>
                <input v-model="form.expiry_date" type="date" class="w-full bg-gray-900 border border-gray-700 rounded-md px-3 py-2 text-gray-200" />
              </div>
            </div>
            <div>
              <label class="block text-gray-300 text-sm mb-1">File</label>
              <input type="file" @change="e=>form.file=e.target.files[0]" class="block w-full text-gray-300" />
            </div>
            <div class="flex justify-end gap-2">
              <button type="button" @click="showUpload=false" class="px-4 py-2 rounded-md bg-gray-700 text-gray-100">Cancel</button>
              <button :disabled="form.processing" class="px-4 py-2 rounded-md bg-gray-600 hover:bg-gray-500 text-gray-100 disabled:opacity-50">Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
