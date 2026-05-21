<!-- resources/js/Pages/Student/Assignments/Index.vue -->
<script setup>
import StudentLayout from '@/Layouts/StudentLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    assignments: {
        type: Array,
        default: () => []
    },
    metrics: {
        type: Object,
        default: () => ({ pending: 0, submitted: 0, graded: 0 })
    }
});

const activeTab = ref('pending');
const selectedAssignment = ref(null);

const uploadForm = useForm({
    file: null,
    comments: ''
});

const openSubmissionModal = (assignment) => {
    selectedAssignment.value = assignment;
    uploadForm.reset();
};

const submitAssignment = () => {
    if (!uploadForm.file) {
        alert('Please attach a file before submitting.');
        return;
    }
    
    uploadForm.post(route('student.assignments.submit', selectedAssignment.value.id), {
        onSuccess: () => {
            selectedAssignment.value = null;
            alert('Assignment uploaded successfully!');
        },
        preserveScroll: true
    });
};
</script>

<template>
    <StudentLayout title="Tasks & Assignments">
        
        <!-- Header Frame -->
        <template #header>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Tasks & Assignments</h1>
                <p class="text-xs text-slate-500 mt-0.5">Submit your practical projects, check timelines, and view feedback.</p>
            </div>
        </template>

        <!-- 📊 QUICK RUNTIME METRIC STRIP -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase">Awaiting Action</span>
                <span class="px-2.5 py-1 bg-rose-50 text-rose-600 font-black text-sm border border-rose-100 rounded-lg">{{ metrics.pending }}</span>
            </div>
            <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase">Under Review</span>
                <span class="px-2.5 py-1 bg-amber-50 text-amber-600 font-black text-sm border border-amber-100 rounded-lg">{{ metrics.submitted }}</span>
            </div>
            <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm flex justify-between items-center">
                <span class="text-xs font-bold text-slate-400 uppercase">Evaluated Tracks</span>
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-black text-sm border border-emerald-100 rounded-lg">{{ metrics.graded }}</span>
            </div>
        </div>

        <!-- 🎛️ VIEW FILTER TABS -->
        <div class="flex items-center space-x-4 border-b border-slate-200 mb-6">
            <button 
                @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 text-xs uppercase tracking-wider border-b-2 font-semibold transition-all px-1"
            >
                Pending Tasks
            </button>
            <button 
                @click="activeTab = 'history'"
                :class="activeTab === 'history' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 text-xs uppercase tracking-wider border-b-2 font-semibold transition-all px-1"
            >
                Submission History
            </button>
        </div>

        <!-- 📑 SUBMISSION DATA LISTINGS -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-wider border-b border-slate-200">
                            <th class="p-4">Assignment & Course</th>
                            <th class="p-4">Timeline / Due Date</th>
                            <th class="p-4">Evaluation Status</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <tr 
                            v-for="task in assignments.filter(t => activeTab === 'pending' ? !t.grade : t.grade)" 
                            :key="task.id"
                            class="hover:bg-slate-50/60 transition-colors"
                        >
                            <td class="p-4">
                                <div class="font-bold text-slate-900 text-sm leading-tight">{{ task.title }}</div>
                                <div class="text-slate-400 text-xs mt-0.5">{{ task.course_title }}</div>
                            </td>
                            <td class="p-4 font-mono text-slate-600">
                                {{ task.due_date }}
                            </td>
                            <td class="p-4">
                                <span 
                                    v-if="task.status === 'graded'" 
                                    class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-md"
                                >
                                    Score: {{ task.grade }}
                                </span>
                                <span 
                                    v-else-if="task.status === 'submitted'" 
                                    class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 font-semibold rounded-md"
                                >
                                    Awaiting Review
                                </span>
                                <span 
                                    v-else 
                                    class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-100 font-semibold rounded-md"
                                >
                                    Missing
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button 
                                    v-if="task.status === 'pending'"
                                    @click="openSubmissionModal(task)"
                                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm transition-colors"
                                >
                                    Upload Solution
                                </button>
                                <span v-else class="text-slate-400 italic">No Actions Open</span>
                            </td>
                        </tr>
                        
                        <tr v-if="assignments.length === 0">
                            <td colspan="4" class="p-8 text-center text-slate-400 italic">
                                No active assignment structures matched your tracking category filter logs.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 📦 SUBMISSION INTERCEPTOR MODAL OVERLAY -->
        <div v-if="selectedAssignment" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xl max-w-md w-full overflow-hidden transition-all transform scale-100">
                <div class="p-5 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base leading-tight">Submit Practical File</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ selectedAssignment.title }}</p>
                    </div>
                    <button @click="selectedAssignment = null" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>

                <form @submit.prevent="submitAssignment" class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Attach Source Code Archive / Document</label>
                        <input 
                            type="file" 
                            @input="uploadForm.file = $event.target.files[0]"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 p-2 rounded-xl"
                        />
                        <div v-if="uploadForm.errors.file" class="text-rose-600 text-xs mt-1">{{ uploadForm.errors.file }}</div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Developer / Student Review Notes</label>
                        <textarea 
                            v-model="uploadForm.comments"
                            rows="3"
                            placeholder="Add notes for your evaluator..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="selectedAssignment = null" 
                            class="px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            :disabled="uploadForm.processing"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-bold text-xs rounded-xl shadow-md transition-all"
                        >
                            {{ uploadForm.processing ? 'Transmitting...' : 'Commit Upload' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </StudentLayout>
</template>