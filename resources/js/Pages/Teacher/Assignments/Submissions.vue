<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    assignment: Object,
    submissions: Array
});

const selectedSubmission = ref(null);

const form = useForm({
    score: '',
    feedback: '',
    status: 'approved'
});

const loadSubmission = (sub) => {
    selectedSubmission.value = sub;
    form.score = sub.score === 'Ungraded' ? '' : sub.score;
    form.feedback = sub.feedback || '';
    form.status = sub.status || 'approved';
};

const submitEvaluation = () => {
    // Submits back to our secure synchronous evaluator endpoint
    form.post(route('teacher.submissions.evaluate', selectedSubmission.value.id), {
        onSuccess: () => {
            selectedSubmission.value = null;
            form.reset();
        }
    });
};
</script>

<template>
    <Head :title="`Submissions - ${assignment.title}`" />

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <Link :href="route('teacher.courses.assignments', course.id)" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                ← Back to Course Assignments Inventory
            </Link>
            <h2 class="text-2xl font-extrabold text-gray-900 mt-2">📥 Turn-In Submissions Matrix</h2>
            <p class="text-sm text-gray-600">Reviewing entries for task: <span class="font-bold">"{{ assignment.title }}"</span> under course {{ course.title }}</p>
        </div>

        <!-- Instructions Rubric Card -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-8">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dispatched Task Instructions Rubric Reference:</h4>
            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ assignment.instructions }}</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- MATRIX GRID LAYER -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Identity Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Turn-In File Attachment Links</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted Date Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grading Score Marks</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Workflow Operational Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="sub in submissions" :key="sub.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ sub.student_name }}</div>
                                    <div class="text-xs text-gray-500">{{ sub.student_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                    <!-- FIXED: Absolute route mapping to storage directory links -->
                                    <a v-if="sub.file_path" :href="`/storage/${sub.file_path}`" target="_blank" class="inline-flex items-center hover:underline">
                                        📂 Download_Submission_Asset.zip
                                    </a>
                                    <span v-else class="text-gray-400 font-normal">No file upload attached</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ sub.created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[sub.score === 'Ungraded' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800', 'px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider']">
                                        {{ sub.score }} {{ sub.score !== 'Ungraded' ? '%' : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button @click="loadSubmission(sub)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        Review & Grade Task
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="submissions.length === 0">
                                <td colspan="5" class="text-center py-8 text-sm text-gray-400">No student submissions found for this assignment matrix block.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WORKSPACE CONTEXT EVALUATION CONTROL PANEL -->
            <div class="bg-gray-900 text-white rounded-xl p-6 h-fit shadow-xl border border-gray-800">
                <h3 class="text-xl font-bold tracking-tight mb-4 border-b border-gray-800 pb-2 text-indigo-400">
                    Control Workspace Panel
                </h3>

                <div v-if="selectedSubmission">
                    <div class="mb-4 bg-gray-800 p-3 rounded-lg border border-gray-700">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Candidate Profile</label>
                        <p class="text-base font-bold text-white">{{ selectedSubmission.student_name }}</p>
                        <p class="text-xs text-gray-400">{{ selectedSubmission.student_email }}</p>
                    </div>

                    <form @submit.prevent="submitEvaluation" class="space-y-4 mt-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Score Matrix (0-100)</label>
                            <input 
                                type="number" 
                                min="0" 
                                max="100" 
                                v-model="form.score"
                                class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" 
                                required
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-2">Review Framework Decision Status</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" value="approved" v-model="form.status" class="text-indigo-600 focus:ring-indigo-500 bg-gray-800 border-gray-700" />
                                    <span class="ml-2 text-xs font-medium text-gray-300">Approve & Log Marks</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" value="rejected" v-model="form.status" class="text-indigo-600 focus:ring-indigo-500 bg-gray-800 border-gray-700" />
                                    <span class="ml-2 text-xs font-medium text-gray-300">Reject / Request Resubmission</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Feedback Rubric Notes</label>
                            <textarea 
                                rows="4" 
                                v-model="form.feedback"
                                placeholder="Add grading feedback remarks..."
                                class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"
                            ></textarea>
                        </div>

                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded transition text-sm disabled:opacity-50"
                        >
                            {{ form.processing ? 'Writing Metrics...' : 'Submit Evaluation' }}
                        </button>
                    </form>
                </div>

                <div v-else class="text-center py-12 text-gray-500 text-sm">
                    Select a student track payload record to initialize the execution engine view.
                </div>
            </div>
        </div>
    </div>
</template>