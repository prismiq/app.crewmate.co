<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({ vessels: Object });
const showModal = ref(false);
const editing = ref(null);
const form = useForm({ name:'', imo_number:'', flag:'', owner:'' });

function openCreate(){ editing.value=null; form.reset(); showModal.value=true; }
function openEdit(v){ editing.value=v; form.reset(); form.name=v.name; form.imo_number=v.imo_number; form.flag=v.flag; form.owner=v.owner; showModal.value=true; }
function submit(){
  if (editing.value) form.put(route('vessels.update', editing.value.id), { onSuccess: close });
  else form.post(route('vessels.store'), { onSuccess: close });
}
function remove(v){ if(confirm('Delete vessel?')) router.delete(route('vessels.destroy', v.id)); }
function close(){ showModal.value=false; }
</script>

<template>
  <Head title="Vessels" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-100">Vessels</h2>
    </template>
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 space-y-4">
      <div class="flex justify-end">
        <button @click="openCreate" class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100 transition">Add Vessel</button>
      </div>
      <div class="overflow-hidden rounded-xl border border-gray-700 bg-gray-800/60">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-gray-800/80">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Name</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">IMO</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300">Flag</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <tr v-for="v in vessels.data" :key="v.id" class="hover:bg-gray-700/30 transition">
              <td class="px-4 py-3 text-gray-200">{{ v.name }}</td>
              <td class="px-4 py-3 text-gray-400">{{ v.imo_number || '—' }}</td>
              <td class="px-4 py-3 text-gray-400">{{ v.flag || '—' }}</td>
              <td class="px-4 py-3 text-right">
                <button @click="openEdit(v)" class="px-3 py-1.5 rounded-md bg-gray-700 text-gray-100 hover:bg-gray-600 transition mr-2">Edit</button>
                <button @click="remove(v)" class="px-3 py-1.5 rounded-md bg-red-700 text-gray-100 hover:bg-red-600 transition">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="showModal" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-700 rounded-xl w-full max-w-lg p-6 space-y-4">
          <h3 class="text-gray-200 text-lg font-semibold">{{ editing ? 'Edit Vessel' : 'Add Vessel' }}</h3>
          <div class="grid grid-cols-1 gap-3">
            <input v-model="form.name" placeholder="Name" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
            <input v-model="form.imo_number" placeholder="IMO Number" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
            <input v-model="form.flag" placeholder="Flag" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
            <input v-model="form.owner" placeholder="Owner" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
          </div>
          <div class="flex justify-end gap-2">
            <button @click="close" class="px-4 py-2 rounded-md bg-gray-700 text-gray-100">Cancel</button>
            <button @click="submit" :disabled="form.processing" class="px-4 py-2 rounded-md bg-gray-600 hover:bg-gray-500 text-gray-100 disabled:opacity-50">Save</button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
