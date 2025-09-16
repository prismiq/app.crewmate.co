<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
const props = defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-100 leading-tight">Dashboard</h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 lg:px-8 space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-5 shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="text-sm text-gray-400">Valid Certificates</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-100">{{ stats?.valid ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-5 shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="text-sm text-gray-400">Expiring Certificates</div>
                        <div class="mt-2 text-3xl font-semibold text-yellow-300">{{ stats?.expiring ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-5 shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="text-sm text-gray-400">Expired Certificates</div>
                        <div class="mt-2 text-3xl font-semibold text-red-400">{{ stats?.expired ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-5 shadow transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="text-sm text-gray-400">Flagged Certificates</div>
                        <div class="mt-2 text-3xl font-semibold text-orange-400">{{ stats?.flagged ?? 0 }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <h3 class="text-gray-200 text-xl font-semibold">Quick Actions</h3>
                    <div class="space-x-3">
                        <Link :href="route('crew.index')" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-gray-100 transition">
                            View Crew Profiles
                        </Link>
                    </div>
                </div>

                <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6">
                    <h4 class="text-gray-200 font-semibold mb-4">Get started</h4>
                    <p class="text-gray-400">Go to Crew to upload and manage certificates.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
