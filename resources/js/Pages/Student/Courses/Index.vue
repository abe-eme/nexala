<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    courses: Array,
    enrolledCourseIds: Array
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8 max-w-7xl mx-auto space-y-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Available Classrooms</h1>
                <p class="text-sm text-slate-500 mt-1">Initialize and join active syllabus sequences created by verified instructors.</p>
            </div>

            <!-- Catalog Matrix Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="course in courses" :key="course.id" class="bg-white border rounded-2xl shadow-sm p-6 flex flex-col justify-between space-y-6 hover:shadow-md transition">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md uppercase tracking-wider">{{ course.category }}</span>
                            <span v-if="enrolledCourseIds.includes(course.id)" class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">✓ Active Vector</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base tracking-tight leading-snug">{{ course.title }}</h3>
                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ course.description }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-50 flex justify-between items-center">
                        <span class="text-xs text-slate-500 font-medium">By {{ course.teacher?.name }}</span>
                        <Link :href="route('student.courses.show', course.id)" class="text-xs font-bold text-indigo-600 hover:underline">Access Terminal →</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>