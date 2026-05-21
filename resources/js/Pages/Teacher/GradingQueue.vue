<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    submissions: Array,  // Assignment pipelines
    quizAttempts: Array  // Quiz audits
});

// Navigation mode state toggle ('assignments' or 'quizzes')
const currentTab = ref('assignments');

// Workspace contextual data loading variables
const selectedItem = ref(null);
const evaluationType = ref(null); 

const form = useForm({
    score: '',
    feedback: '',
    status: 'approved'
});

const loadAssignment = (submission) => {
    evaluationType.value = 'assignment';
    selectedItem.value = submission;
    // Safely assign score without breaking the reactive number input field
    form.score = submission.score === 'Ungraded' ? '' : submission.score;
    form.feedback = submission.feedback || '';
    form.status = submission.status || 'approved';
};

const loadQuiz = (attempt) => {
    evaluationType.value = 'quiz';
    selectedItem.value = attempt;
    form.score = attempt.score; 
    form.feedback = attempt.feedback || '';
    form.status = attempt.status || 'approved';
};

const submitEvaluation = () => {
    const url = evaluationType.value === 'assignment' 
        ? route('teacher.submissions.evaluate', selectedItem.value.id)
        : route('teacher.quizzes.evaluate', selectedItem.value.id);

    form.post(url, {
        onSuccess: () => {
            selectedItem.value = null;
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Synchronous Grading Hub" />

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Synchronous Grading Evaluation Terminal</h2>

        <!-- Pipeline Navigation Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button 
                    @click="currentTab = 'assignments'; selectedItem = null"
                    :class="[currentTab === 'assignments' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']"
                >
                    Assignment Pipelines
                </button>
                <button 
                    @click="currentTab = 'quizzes'; selectedItem = null"
                    :class="[currentTab === 'quizzes' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']"
                >
                    Quiz Performance Streams
                </button>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- PIPELINE DATA STREAM VIEWER (LEFT SIDE) -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Identity Name</th>
                                <th v-if="currentTab === 'assignments'" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Turn-In File Attachment Links</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted Date Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grading Score Marks</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Workflow Operational Actions</th>
                            </tr>
                        </thead>
                        
                        <!-- ASSIGNMENTS ITERATION LAYER -->
                        <tbody v-if="currentTab === 'assignments'" class="bg-white divide-y divide-gray-200">
                            <tr v-for="sub in submissions" :key="sub.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ sub.student_name || 'Unknown Student Account' }}</div>
                                    <div class="text-xs text-gray-500">{{ sub.student_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                    <a v-if="sub.file_path" :href="`/storage/${sub.file_path}`" target="_blank" class="hover:underline">
                                        📂 Download_Submission_Asset.zip
                                    </a>
                                    <span v-else class="text-gray-400">No Asset Submitted</span>
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
                                    <button @click="loadAssignment(sub)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        Review & Grade Task
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="submissions.length === 0">
                                <td colspan="5" class="text-center py-8 text-sm text-gray-400">No student assignment records tracked inside this stream payload.</td>
                            </tr>
                        </tbody>

                        <!-- QUIZZES ITERATION LAYER -->
                        <tbody v-else class="bg-white divide-y divide-gray-200">
                            <tr v-for="quiz in quizAttempts" :key="quiz.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ quiz.student_name || 'Unknown Student Account' }}</div>
                                    <div class="text-xs text-gray-500">{{ quiz.student_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ quiz.created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-indigo-100 text-indigo-800 px-2.5 py-1 rounded-full text-xs font-bold">
                                        Auto-Score: {{ quiz.score }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button @click="loadQuiz(quiz)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        Review & Grade Task
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="quizAttempts.length === 0">
                                <td colspan="4" class="text-center py-8 text-sm text-gray-400">No automated quiz runtime attempts captured inside this pipeline payload.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WORKSPACE CONTEXT EVALUATION CONTROL PANEL (RIGHT SIDE) -->
            <div class="bg-gray-900 text-white rounded-xl p-6 h-fit shadow-xl border border-gray-800">
                <h3 class="text-xl font-bold tracking-tight mb-4 border-b border-gray-800 pb-2 text-indigo-400">
                    Control Workspace Panel
                </h3>

                <div v-if="selectedItem">
                    <div class="mb-4 bg-gray-800 p-3 rounded-lg border border-gray-700">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Candidate Profile</label>
                        <p class="text-base font-bold text-white">{{ selectedItem.student_name }}</p>
                        <p class="text-xs text-gray-400">{{ selectedItem.student_email }}</p>
                        <p class="text-xs text-indigo-300 mt-1 font-medium">Module Focus: {{ selectedItem.course_title }}</p>
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
                                <label class="inline-flex items-center cursor-pointer" v-if="evaluationType === 'assignment'">
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
                                placeholder="Add technical comments..."
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