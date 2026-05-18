<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({ auth: Object, course: Object, assignments: Array });
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Assignment Project Task Management" />
        <div class="p-8 max-w-5xl mx-auto text-xs mt-4 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <Link :href="route('teacher.courses.index')" class="text-emerald-600 hover:underline font-bold">← Back</Link>
                    <h1 class="text-xl font-black text-slate-900 mt-1">{{ course.title }} — Assignment Tasks Inventory</h1>
                </div>
                <Link :href="route('teacher.courses.assignments.create', course.id)" class="bg-slate-900 text-white font-bold px-4 py-2 rounded-xl">
                    ➕ Create Assignment Project
                </Link>
            </div>

            <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
                <div v-if="assignments.length === 0" class="p-12 text-center text-slate-400 italic">No structural course project assignment briefs have been dispatched to this course catalog yet.</div>
                <div v-else class="divide-y divide-slate-100">
                    <div v-for="assign in assignments" :key="assign.id" class="p-5 hover:bg-slate-50/40 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                        
                        <div class="space-y-1 max-w-xl">
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wide">{{ assign.title }}</h2>
                            
                            <!-- Document Export Actions Row Group Area -->
                            <div class="flex items-center gap-3 pt-1">
                                <span class="font-bold text-slate-400 uppercase text-[10px]">Export Spec Document:</span>
                                
                                <!-- 🔴 Fixed PDF download link array parameter -->
                                <a :href="route('teacher.assignments.download.pdf', { assignment: assign.id })" class="text-red-600 hover:text-red-700 font-bold flex items-center gap-1 transition-colors">
                                    🔴 Download PDF File
                                </a>
                                <span class="text-slate-300">|</span>
                                
                                <!-- 🔵 Fixed Word download link array parameter -->
                                <a :href="route('teacher.assignments.download.word', { assignment: assign.id })" class="text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1 transition-colors">
                                    🔵 Download Word Docx
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <Link :href="route('teacher.assignments.submissions', { course: course.id, assignment: assign.id })" class="bg-slate-900 hover:bg-black text-white font-bold px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors text-center">
                                Submissions Pipeline →
                            </Link>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>