<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    auth: Object,
    course: Object,
    quizzes: Array
});

const form = useForm({
    question_type: 'multiple_choice',
    question_text: '',
    option_a: '',
    option_b: '',
    correct_option: ''
});

const isGeneratingAI = ref(false);

const triggerAIAssistantHelp = () => {
    isGeneratingAI.value = true;
    
    setTimeout(() => {
        if (form.question_type === 'multiple_choice') {
            form.question_text = `Regarding the course "${props.course.title}", which option serves as a core fundamental approach to problem-solving within the scope of ${props.course.category}?`;
            form.option_a = "Primary core systematic structure framework implementation";
            form.option_b = "Secondary random historical variant baseline anomaly alternative";
            form.correct_option = "A";
        } else if (form.question_type === 'true_false') {
            form.question_text = `True or False: Advanced parameters in ${props.course.title} must bypass standard optimization steps during low-scale executions.`;
            form.option_a = "True";
            form.option_b = "False";
            form.correct_option = "False";
        } else if (form.question_type === 'fill_blank') {
            form.question_text = "The system operational layer depends on ________ protocols to synchronize processing values securely.";
            form.correct_option = "REST API";
        } else if (form.question_type === 'short_answer') {
            form.question_text = "Provide a comprehensive written paragraph explaining how memory stack limits modify script execution lifecycle metrics.";
            form.correct_option = "Manual instructor evaluation scoring matrix required.";
        } else {
            form.question_text = "Calculate the maximum performance throughput efficiency bounds given data load (N = 1024) and execution runtime limits.";
            form.correct_option = "Solution requires manual structural equation grading review loops.";
        }
        isGeneratingAI.value = false;
    }, 850);
};

const saveQuestion = () => {
    form.post(route('teacher.courses.quizzes.store', props.course.id), {
        onSuccess: () => {
            form.reset('question_text', 'option_a', 'option_b', 'correct_option');
        }
    });
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Course Quiz Management Hub" />

        <div class="p-8 max-w-5xl mx-auto mt-4 space-y-8 text-xs">
            
            <!-- Breadcrumbs Header Section -->
            <div>
                <Link :href="route('teacher.courses.index')" class="text-xs font-bold text-emerald-600 hover:underline">
                    ← Back to Courses Master Table
                </Link>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Quiz Blueprint Builder Hub — {{ course.title }}</h1>
                <p class="text-slate-500">Formulate multiple-choice paths, short paragraphs, true/false checkpoints, or long practical workout assignments below.</p>
            </div>

            <!-- Quiz Entry Configuration Box Wrapper -->
            <div class="bg-white border rounded-2xl shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">🛠️ Configure Current Active Examination Item</h2>
                    
                    <!-- AI Help Prompt Button Generator Target -->
                    <button 
                        type="button" 
                        @click="triggerAIAssistantHelp"
                        class="bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold border border-purple-200 px-3 py-1.5 rounded-xl transition-all cursor-pointer flex items-center gap-1"
                    >
                        ✨ {{ isGeneratingAI ? 'AI Engine Formulating Concepts...' : 'Need AI Help Generating Content Layout?' }}
                    </button>
                </div>

                <form @submit.prevent="saveQuestion" class="space-y-4">
                    <!-- Format Selection Switcher Widget Component -->
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Select Question Style Format Variant</label>
                        <select v-model="form.question_type" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none font-medium text-slate-800">
                            <option value="multiple_choice">🎨 Multiple Choice Layout Form (A or B Choice Grid)</option>
                            <option value="true_false">⚖️ True / False Toggle Choice Option Checkbox</option>
                            <option value="fill_blank">✏️ Fill In The Blank Text Blanks Assignment</option>
                            <option value="short_answer">📝 Short Theoretical Written Paragraph Response</option>
                            <option value="workout">🧮 Practical Exercise Workout Problem Scenario</option>
                        </select>
                    </div>

                    <!-- Question Text Prompt Input Box Container -->
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Question Prompt prompt text</label>
                        <textarea v-model="form.question_text" rows="3" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none text-slate-800 font-medium leading-relaxed" placeholder="Write out your structural test prompt parameters or workout descriptions here..." required></textarea>
                    </div>

                    <!-- Render Choice Options Parameters ONLY IF layout format contains true_false or multiple_choice patterns -->
                    <div v-if="form.question_type === 'multiple_choice' || form.question_type === 'true_false'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Option Choice Option A / True Label</label>
                            <input v-model="form.option_a" type="text" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none" :disabled="form.question_type === 'true_false'" :placeholder="form.question_type === 'true_false' ? 'Locked to True' : 'Enter Choice A label value'" />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Option Choice Option B / False Label</label>
                            <input v-model="form.option_b" type="text" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none" :disabled="form.question_type === 'true_false'" :placeholder="form.question_type === 'true_false' ? 'Locked to False' : 'Enter Choice B label value'" />
                        </div>
                    </div>

                    <!-- Evaluation Key Field Target String -->
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Correct Answer Evaluation Benchmark</label>
                        <input v-model="form.correct_option" type="text" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none placeholder-slate-400 font-mono text-slate-800 font-bold" placeholder="e.g. A, True, 'REST API', or write custom manual rubrics evaluation notes..." required />
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-3 rounded-xl uppercase tracking-wider transition-colors">
                        Add Question Entry to Active Examination Database
                    </button>
                </form>
            </div>

            <!-- 📝 CREATED QUESTIONS REPOSITORY LIST GRIDS -->
            <div class="space-y-3">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">📋 Existing Question Bank Profile List ({{ quizzes.length }} Added)</h2>
                
                <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
                    <div v-if="quizzes.length === 0" class="p-8 text-center text-slate-400 italic">
                        No examination questions created or mapped to this course profile parameters yet. Complete fields above.
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        <div v-for="(quiz, index) in quizzes" :key="quiz.id" class="p-5 hover:bg-slate-50/50 transition-colors flex justify-between items-start">
                            <div class="space-y-1.5 max-w-4xl">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono bg-slate-900 text-white font-bold text-[10px] px-1.5 py-0.5 rounded">Question Entry #{{ index + 1 }}</span>
                                    <span class="font-mono text-slate-500 font-bold uppercase text-[10px] bg-slate-100 border px-1.5 rounded">Format style: {{ quiz.question_type }}</span>
                                </div>
                                <p class="text-slate-900 font-bold text-sm leading-relaxed whitespace-pre-wrap">{{ quiz.question_text }}</p>
                                
                                <div v-if="quiz.question_type === 'multiple_choice' || quiz.question_type === 'true_false'" class="text-[11px] text-slate-500 space-x-4 pl-1 font-medium">
                                    <span>🟢 Option A: {{ quiz.option_a || 'True' }}</span>
                                    <span>🔵 Option B: {{ quiz.option_b || 'False' }}</span>
                                </div>
                                
                                <div class="text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded inline-block font-mono font-bold">
                                    🎯 Verified Correct Anchor Value: "{{ quiz.correct_option }}"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </TeacherSidebarLayout>
</template>