<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const form = useForm({ crew_code:'', first_name:'', last_name:'', email:'', phone:'', rank:'', nationality:'', date_of_birth:'', address_line1:'', address_line2:'', city:'', state:'', postal_code:'', country:'' });
function submit(){ form.post(route('crew.store')); }
</script>

<template>
  <Head title="Add Crew" />
  <AuthenticatedLayout>
    <template #header><h2 class="font-semibold text-2xl text-gray-100">Add Crew</h2></template>
    <div class="max-w-3xl mx-auto px-4 lg:px-8 py-8 space-y-3">
      <div class="grid grid-cols-2 gap-3">
        <input v-model="form.crew_code" placeholder="Crew Code" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.rank" placeholder="Rank" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.first_name" placeholder="First Name" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.last_name" placeholder="Last Name" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.email" placeholder="Email" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.phone" placeholder="Phone" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.nationality" placeholder="Nationality" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 col-span-2" />
        <input v-model="form.date_of_birth" type="date" placeholder="Date of Birth" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 col-span-2" />
        <input v-model="form.address_line1" placeholder="Address line 1" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 col-span-2" />
        <input v-model="form.address_line2" placeholder="Address line 2" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2 col-span-2" />
        <input v-model="form.city" placeholder="City" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.state" placeholder="State/Region" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.postal_code" placeholder="Postal Code" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
        <input v-model="form.country" placeholder="Country" class="bg-gray-900 border border-gray-700 text-gray-200 rounded-md px-3 py-2" />
      </div>
      <div class="flex justify-end">
        <button @click="submit" :disabled="form.processing" class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100">Create</button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
