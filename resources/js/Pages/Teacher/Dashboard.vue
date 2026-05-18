<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Teacher Workspace Hub" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Teacher Management Dashboard Workspace</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl x-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        Welcome back, Instructor! Here you can create courses, build lessons, and manage assignments safely.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>