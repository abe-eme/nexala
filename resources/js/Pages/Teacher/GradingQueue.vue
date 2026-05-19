<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import TeacherSidebarLayout from '../Layouts/TeacherSidebarLayout.vue';

// Dummy incoming student submissions data for local visual testing
const mockSubmissions = ref([
    {
        id: 1,
        student_name: 'Alex Rivera',
        assignment_title: 'Database Migrations & Core Schema Design',
        submitted_at: 'May 18, 2026 - 14:32',
        repo_url: 'https://github.com/alex-dev/nexus-db-milestone',
        notes: 'I completed all core requirements and set up the foreign key constraint indexes as requested. Please review the database seeder classes.',
        status: 'pending'
    },
    {
        id: 2,
        student_name: 'Sophia Chen',
        assignment_title: 'Vue Single File Components Setup',
        submitted_at: 'May 19, 2026 - 09:15',
        repo_url: 'https://github.com/sophia-c/nexus-frontend-views',
        notes: 'Had a bit of a challenge binding the Tailwind layout state toggles properly, but got it resolved in the final compilation asset bundle.',
        status: 'pending'
    }
]);

// Selected submission for the active evaluation view
const selectedSubmission = ref(mockSubmissions.value[0]);

const selectSubmission = (submission) => {
    selectedSubmission.value = submission;
    form.score = '';
    form.feedback = '';
};

// Inertia Form State Management for Asynchronous Evaluation Processing
const form = useForm({
    score: '',
    feedback: '',
    status: 'approved'
});

const submitEvaluation = (id) => {
    form.post(route('teacher.submissions.evaluate', id), {
        onSuccess: () => {
            alert('Evaluation uploaded successfully!');
            selectedSubmission.value.status = form.status;
        }
    });
};
</script>

<template>
    <Head title="Grading Evaluation Terminal" />

    <TeacherSidebarLayout>
        <div class="p-8 max-w-7xl mx-auto">
            <!-- Header Module -->
            <div class="mb-8">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Grading Evaluation Terminal</h1>
                <p class="text-sm text-slate-500 mt-1">Review student task assets, configure progression flags, and provide engineering feedback.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- LEFT PANEL: Incoming Queue Row Selection List -->
                <div class="lg:col-span-5 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Awaiting Assessment</h3>
                    
                    <div class="space-y-3">
                        <button 
                            v-for="sub in mockSubmissions" 
                            :key="sub.id"
                            @click="selectSubmission(sub)"
                            :class="[selectedSubmission?.id === sub.id ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-white' : 'border-slate-200 hover:border-slate-300 bg-white']"
                            class="w-full text-left p-5 rounded-xl border transition-all shadow-sm flex flex-col justify-between"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-slate-900 text-sm truncate max-w-[200px]">{{ sub.student_name }}</h4>
                                <span :class="[sub.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200']" class="text-[10px] px-2 py-0.5 rounded-full border font-semibold uppercase tracking-wider">
                                    {{ sub.status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 font-medium line-clamp-1 mb-3">{{ sub.assignment_title }}</p>
                            <span class="text-[11px] text-slate-400">Submitted: {{ sub.submitted_at }}</span>
                        </button>
                    </div>
                </div>

                <!-- RIGHT PANEL: Full Workspace Specification & Interactive Actions Panel -->
                <div class="lg:col-span-7">
                    <div v-if="selectedSubmission" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                        <!-- Submission Core Parameters Info Header -->
                        <div class="border-b border-slate-100 pb-5">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">Active Evaluation Entry</span>
                            <h2 class="text-lg font-bold text-slate-900 mt-3">{{ selectedSubmission.student_name }}</h2>
                            <p class="text-sm text-slate-600 mt-1 font-medium">{{ selectedSubmission.assignment_title }}</p>
                        </div>

                        <!-- Provided Repository Asset Payload Link -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Project Repository Resource</label>
                            <div class="flex items-center space-x-2">
                                <a :href="selectedSubmission.repo_url" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 font-semibold underline flex items-center gap-1.5 break-all">
                                    <span>🔗</span> {{ selectedSubmission.repo_url }}
                                </a>
                            </div>
                        </div>

                        <!-- Optional Student Progress Notes Summary -->
                        <div class="space-y-2" v-if="selectedSubmission.notes">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Student Submission Notes</label>
                            <p class="text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed whitespace-pre-wrap">
                                "{{ selectedSubmission.notes }}"
                            </p>
                        </div>

                        <!-- Interactive Grading Metric Evaluation Input Controls Form Block -->
                        <form @submit.prevent="submitEvaluation(selectedSubmission.id)" class="border-t border-slate-100 pt-5 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Numeric Score Output Field -->
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700">Assign Score (0-100)</label>
                                    <input 
                                        type="number" 
                                        v-model="form.score" 
                                        min="0" 
                                        max="100" 
                                        required
                                        placeholder="e.g. 92"
                                        class="w-full text-sm border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 rounded-xl px-4 py-2.5 transition-colors"
                                    />
                                </div>
                                <!-- Progression State Evaluation Toggles -->
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-slate-700">Assessment Decision</label>
                                    <select 
                                        v-model="form.status" 
                                        class="w-full text-sm border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 rounded-xl px-4 py-2.5 transition-colors"
                                    >
                                        <option value="approved">Approve & Progress</option>
                                        <option value="rejected">Request Re-submission</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Constructive Feedback Textarea Input -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-700">Constructive Feedback Rubric</label>
                                <textarea 
                                    v-model="form.feedback" 
                                    rows="4" 
                                    placeholder="Provide notes on architectural layout, test status vectors, and clean refactoring patterns..."
                                    class="w-full text-sm border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20 rounded-xl p-4 transition-colors resize-none"
                                ></textarea>
                            </div>

                            <!-- Final Form Execution Trigger Button -->
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-sm transition-all duration-150 flex items-center justify-center space-x-2"
                            >
                                <span>💾</span>
                                <span>{{ form.processing ? 'Saving Review Record...' : 'Publish Evaluation Score' }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- No Selection Placeholder State -->
                    <div v-else class="h-64 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center text-slate-400">
                        <span class="text-2xl mb-2">📥</span>
                        <p class="text-sm font-medium">Select a submission row container from the queue to execute evaluations.</p>
                    </div>
                </div>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>