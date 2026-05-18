<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Access all logged-in session data dynamically
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Teacher Workspace Hub" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Instructor Workspace Panel
                </h2>
                <!-- Badge indicating system clearance role context status -->
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold uppercase tracking-wider rounded-full border border-indigo-200 dark:border-indigo-800">
                    {{ user.role }} Profile Verified
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8">
                        <div class="space-y-2">
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                                Welcome back, {{ user.name }}!
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-xl">
                                Access your global courses structure, upload dynamic rich lesson resources, and review student grades securely.
                            </p>
                        </div>

                        <hr class="my-6 border-gray-100 dark:border-gray-700" />

                        <!-- Quick Visual Analytics Micro-Cards Grid Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Active Courses</span>
                                <span class="text-4xl font-black text-gray-900 dark:text-white mt-1 block">0</span>
                            </div>
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Enrolled Students</span>
                                <span class="text-4xl font-black text-gray-900 dark:text-white mt-1 block">0</span>
                            </div>
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Logged IP Channel Address</span>
                                <span class="text-sm font-mono text-indigo-600 dark:text-indigo-400 mt-3 block font-bold">{{ user.ip_address }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>