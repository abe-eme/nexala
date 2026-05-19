<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

// Mock data representing incoming self-paced student file submissions
const props = defineProps({
    submissions: {
        type: Array,
        default: () => [
            { id: 101, student_name: 'John Doe', course_title: 'Introduction to Full-Stack Web Architectures', file_name: 'final_architecture_lab.pdf', submitted_at: '2026-05-18' },
            { id: 102, student_name: 'Jane Smith', course_title: 'Relational Database Engine Engineering', file_name: 'indexing_matrix_v2.docx', submitted_at: '2026-05-19' }
        ]
    }
});

const activeSubmission = ref(null);
const gradeScore = ref('');
const feedbackText = ref('');

const selectSubmission = (submission) => {
    activeSubmission.value = submission;
    gradeScore.value = '';
    feedbackText.value = '';
};

const submitEvaluation = () => {
    if (!gradeScore.value || gradeScore.value < 0 || gradeScore.value > 100) {
        alert('Please enter a valid evaluation score between 0 and 100.');
        return;
    }

    router.post(route('teacher.submissions.evaluate', activeSubmission.value.id), {
        score: gradeScore.value,
        feedback: feedbackText.value,
        status: gradeScore.value >= 80 ? 'approved' : 'rejected'
    }, {
        onSuccess: () => {
            alert('Evaluation submitted successfully. Student progress matrix updated.');
            activeSubmission.value = null;
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#f8fafc] text-slate-800 p-6 md:p-8 font-sans antialiased text-xs">
        <Head title="Teacher Evaluation Terminal" />

        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header section -->
            <div class="border-b pb-4 border-slate-200">
                <p class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-widest">Asynchronous Evaluation Queue</p>
                <h2 class="text-lg font-black uppercase tracking-tight mt-1">Teacher Assessment Gateway</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: The Live Incoming Queue List -->
                <div class="lg:col-span-1 border rounded-2xl p-5 bg-white border-slate-200 space-y-4 shadow-xs">
                    <h3 class="font-bold uppercase tracking-wider text-[11px] text-slate-500">Pending Evaluation Stream</h3>
                    
                    <div v-if="submissions.length === 0" class="p-8 text-center font-mono text-slate-400 border border-dashed rounded-xl">
                        Zero pending submissions in queue.
                    </div>

                    <div v-else class="space-y-2">
                        <button 
                            v-for="sub in submissions" 
                            :key="sub.id"
                            @click="selectSubmission(sub)"
                            type="button"
                            :class="[activeSubmission?.id === sub.id ? 'border-indigo-600 bg-indigo-50/40' : 'border-slate-200 hover:bg-slate-50']"
                            class="w-full text-left p-4 border rounded-xl transition-all flex flex-col space-y-2 cursor-pointer"
                        >
                            <div class="flex justify-between items-start">
                                <span class="font-bold text-slate-900 text-[13px]">{{ sub.student_name }}</span>
                                <span class="font-mono text-[9px] text-slate-400">{{ sub.submitted_at }}</span>
                            </div>
                            <p class="text-slate-500 font-medium truncate">{{ sub.course_title }}</p>
                            <span class="font-mono text-[10px] text-indigo-600 underline truncate">📄 {{ sub.file_name }}</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Interactive Grading Canvas Panel -->
                <div class="lg:col-span-2 border rounded-2xl p-6 bg-white border-slate-200 shadow-xs">
                    <div v-if="!activeSubmission" class="h-64 flex flex-col items-center justify-center text-center space-y-2 border border-dashed border-slate-200 rounded-xl">
                        <span class="text-xl">📥</span>
                        <p class="font-mono text-slate-400 uppercase tracking-wider text-[10px]">Select a student submission from the queue stream to initialize grading evaluation</p>
                    </div>

                    <div v-else class="space-y-6">
                        <div class="border-b pb-3 border-slate-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-tight text-slate-900">Evaluating: {{ activeSubmission.student_name }}</h3>
                                <p class="text-slate-400 text-[11px] mt-0.5">{{ activeSubmission.course_title }}</p>
                            </div>
                            <a href="#" class="bg-slate-100 hover:bg-slate-200 font-mono font-bold px-3 py-1.5 rounded-lg text-[9px] uppercase tracking-wider transition-all">
                                Download File ↓
                            </a>
                        </div>

                        <!-- Grading Form Block -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1 space-y-2">
                                <label class="block font-mono font-bold text-slate-500 uppercase tracking-wider">Metric Score (Max 100)</label>
                                <input 
                                    v-model="gradeScore"
                                    type="number" 
                                    min="0" 
                                    max="100" 
                                    placeholder="e.g. 85" 
                                    class="w-full p-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-mono font-bold text-sm bg-slate-50"
                                />
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="block font-mono font-bold text-slate-500 uppercase tracking-wider">Qualitative Feedback Matrix</label>
                                <textarea 
                                    v-model="feedbackText"
                                    rows="3" 
                                    placeholder="Provide detailed engineering feedback regarding code structural optimization or architectural execution..."
                                    class="w-full p-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50"
                                ></textarea>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                            <button 
                                @click="activeSubmission = null"
                                type="button" 
                                class="px-4 py-2 border border-slate-200 rounded-xl font-mono font-bold text-slate-500 hover:bg-slate-50 cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="submitEvaluation"
                                type="button" 
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-mono font-black tracking-wider uppercase shadow-md shadow-indigo-600/10 cursor-pointer"
                            >
                                Commit Grade Decision
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>