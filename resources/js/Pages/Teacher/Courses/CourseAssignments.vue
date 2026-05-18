<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

const props = defineProps({
    auth: Object,
    course: Object,
    assignments: Array,
    submittedAssignments: Array 
});

const assignForm = useForm({
    title: '',
    instructions: ''
});

const triggerAssignmentHelper = () => {
    assignForm.title = `Final Course Capstone Assignment - ${props.course.title}`;
    assignForm.instructions = `Please create a comprehensive practical summary assignment covering all tools used during the course chapters. Submit your final documentation file here for instructor manual review.`;
};

const saveAssignment = () => {
    assignForm.post(route('teacher.courses.assignments.store', props.course.id), {
        onSuccess: () => assignForm.reset()
    });
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Course Assignment" />

        <div class="p-8 max-w-6xl mx-auto space-y-6">
            <div>
                <Link :href="route('teacher.courses.index')" class="text-xs font-bold text-emerald-600 hover:underline">
                    ← Back to Courses Main Table
                </Link>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Course Capstone Assignment: {{ course.title }}</h1>
                <p class="text-slate-500 text-xs">A comprehensive task or project assigned to students at the end of the course curriculum path.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left 2 Columns: Active Briefs + Student File Upload Logs Table -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Assignment Brief</h3>
                        <div v-if="assignments.length === 0" class="bg-white p-6 rounded-xl border border-dashed text-center text-xs text-slate-400 italic">
                            No project briefs configured for this course yet.
                        </div>
                        <div v-for="task in assignments" :key="task.id" class="bg-white p-4 border rounded-xl shadow-sm text-xs">
                            <h4 class="font-bold text-sm text-slate-900">{{ task.title }}</h4>
                            <p class="text-slate-600 mt-1 leading-relaxed">{{ task.instructions }}</p>
                        </div>
                    </div>

                    <!-- STUDENT SUBMISSIONS ARCHIVE TABLE -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Turned-In Student Submissions</h3>
                        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="bg-slate-50 border-b font-bold text-slate-400 uppercase">
                                    <tr>
                                        <th class="p-3 pl-4">Student Learner Name</th>
                                        <th class="p-3">Uploaded Response Material</th>
                                        <th class="p-3">Turned In Date</th>
                                        <th class="p-3 text-right pr-4">Grading Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y text-slate-700">
                                    <tr v-if="submittedAssignments.length === 0">
                                        <td colspan="4" class="p-6 text-center text-slate-400 italic">No student task files uploaded yet.</td>
                                    </tr>
                                    <tr v-for="sub in submittedAssignments" :key="sub.id">
                                        <td class="p-3 pl-4 font-bold text-slate-900">{{ sub.user_name }}</td>
                                        <td class="p-3 text-emerald-600 underline cursor-pointer font-medium">📄 Download Project File</td>
                                        <td class="p-3 text-slate-400">{{ sub.submitted_at }}</td>
                                        <td class="p-3 text-right pr-4 font-bold text-slate-900">
                                            {{ sub.grade_score || 'Pending Grade Review' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Form Column -->
                <div class="bg-white border p-5 rounded-2xl shadow-sm h-fit space-y-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h3 class="font-bold text-sm text-slate-900">Configure Assignment</h3>
                        <button type="button" @click="triggerAssignmentHelper" class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                            ✨ Suggest Brief
                        </button>
                    </div>

                    <form @submit.prevent="saveAssignment" class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Task Title</label>
                            <input v-model="assignForm.title" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Instructions Brief</label>
                            <textarea v-model="assignForm.instructions" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white text-xs font-bold py-2 rounded-xl transition-colors">
                            Save Assignment Outline
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>