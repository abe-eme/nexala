<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({
    auth: Object,
    course: Object,
    assignment: Object,
    submissions: Array // Tracks student submitted assignment entries safely
});
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Review Student Project Submissions" />
        
        <div class="p-8 max-w-5xl mx-auto text-xs mt-4 space-y-4">
            <!-- Navigation Breadcrumb Header Block Area -->
            <div>
                <Link :href="route('teacher.courses.assignments', course.id)" class="text-emerald-600 hover:underline font-bold">
                    ← Back to Course Assignments Inventory
                </Link>
                <div class="flex items-start justify-between mt-2">
                    <div>
                        <h1 class="text-xl font-black text-slate-900 uppercase tracking-wide">
                            📥 Turn-In Submissions Matrix
                        </h1>
                        <p class="text-slate-500 mt-1">
                            Reviewing entries for task: <span class="font-bold text-slate-800">"{{ assignment.title }}"</span> 
                            under course <span class="font-bold text-slate-800">{{ course.title }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Task Instructions Reference Metadata Container -->
            <div class="bg-slate-50 border p-4 rounded-xl text-slate-700 leading-relaxed">
                <span class="font-bold text-slate-900 block uppercase text-[10px] tracking-wider mb-0.5">Dispatched Task Instructions Rubric Reference:</span>
                <p class="whitespace-pre-wrap text-[11px]">{{ assignment.instructions }}</p>
            </div>

            <!-- Main Student Submission Records Interactive Table Board -->
            <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white uppercase font-bold border-b text-[10px] tracking-wider">
                            <th class="p-4">Student Identity Name</th>
                            <th class="p-4">Turn-In File Attachment Links</th>
                            <th class="p-4">Submitted Date Timestamp</th>
                            <th class="p-4 text-center">Grading Score Marks</th>
                            <th class="p-4 text-right">Workflow Operational Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <!-- Conditional Safe State Loop Check Fallback Logic -->
                        <tr v-if="!submissions || submissions.length === 0">
                            <td colspan="5" class="p-12 text-center text-slate-400 italic bg-slate-50/20">
                                No student turn-in records or code file submissions found submitted for this deployment baseline profile yet.
                            </td>
                        </tr>
                        
                        <!-- Populated Active Submissions Iteration Module Loop -->
                        <tr v-else v-for="sub in submissions" :key="sub.id" class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-4 font-bold text-slate-900">{{ sub.student?.name || 'Unknown Student Account' }}</td>
                            <td class="p-4">
                                <a :href="sub.file_url" target="_blank" class="text-blue-600 font-mono hover:underline font-bold">
                                    📂 {{ sub.file_name || 'Download_Submission_Asset.zip' }}
                                </a>
                            </td>
                            <td class="p-4 text-slate-500 font-mono">{{ sub.created_at || 'Just Now' }}</td>
                            <td class="p-4 text-center">
                                <span v-if="sub.grade" class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-bold font-mono text-[11px]">
                                    {{ sub.grade }} / 100
                                </span>
                                <span v-else class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-bold font-mono text-[10px] uppercase">
                                    Ungraded
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button type="button" class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-3 py-1 rounded-lg font-bold transition-colors">
                                    Review & Grade Task
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>