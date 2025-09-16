<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
const props = defineProps({ crew: Object });
const form = useForm({
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
function submit(){ form.put(route('crew.update', props.crew.id)); }
</script>

<template>
  <Head :title="`Edit ${crew.full_name}`" />
  <AuthenticatedLayout>
    <template #header><h2 class="font-semibold text-2xl text-gray-100">Edit {{ crew.full_name }}</h2></template>
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
      <div class="flex justify-between">
        <form :action="route('crew.destroy', crew.id)" method="post" @submit.prevent="form.delete(route('crew.destroy', crew.id))">
          <button class="px-4 py-2 rounded-md bg-red-700 hover:bg-red-600 text-gray-100">Delete</button>
        </form>
        <button @click="submit" :disabled="form.processing" class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100">Save</button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
