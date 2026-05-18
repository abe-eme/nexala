<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';
import { ref } from 'vue';

const props = defineProps({ auth: Object, course: Object });
const form = useForm({
    question_type: 'multiple_choice',
    question_text: '',
    option_a: '',
    option_b: '',
    option_c: '',
    option_d: '',
    correct_option: ''
});

const isGeneratingAI = ref(false);

const runAIEngine = () => {
    isGeneratingAI.value = true;
    setTimeout(() => {
        if (form.question_type === 'multiple_choice') {
            form.question_text = "Which element acts as the primary architectural controller standard?";
            form.option_a = "Central Validation Broker Routing Hub";
            form.option_b = "Distributed Temporary File Memory Node";
            form.option_c = "Asynchronous Secondary Storage Interface Buffer";
            form.option_d = "Local Non-Persistent Layout Array Pointer";
            form.correct_option = "A";
        } else if (form.question_type === 'true_false') {
            form.question_text = "True or False: System configuration logs compile sequentially during live deployments.";
            form.option_a = "True"; form.option_b = "False"; form.correct_option = "True";
        } else {
            form.question_text = "Input a detailed technical solution workflow summary configuration rule.";
            form.correct_option = "Verified system rubric rule parameters.";
        }
        isGeneratingAI.value = false;
    }, 700);
};

const saveQuestion = () => {
    form.post(route('teacher.courses.quizzes.store', props.course.id));
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Formulate Examination Questions" />
        <div class="p-8 max-w-2xl mx-auto bg-white border rounded-2xl shadow-sm text-xs mt-6 space-y-4">
            <div class="flex justify-between items-center">
                <h1 class="text-base font-black text-slate-900 uppercase">➕ Create New Multi-Format Question</h1>
                <button type="button" @click="runAIEngine" class="bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold border border-purple-200 px-3 py-1 rounded-xl">
                    ✨ {{ isGeneratingAI ? 'Thinking...' : 'AI Help Content' }}
                </button>
            </div>

            <form @submit.prevent="saveQuestion" class="space-y-4">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Question Type Style Variant</label>
                    <select v-model="form.question_type" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none text-slate-800 font-bold">
                        <option value="multiple_choice">🎨 Multiple Choice (A, B, C, D 4 Options)</option>
                        <option value="true_false">⚖️ True / False Choice Toggles</option>
                        <option value="fill_blank">✏️ Fill In The Blank Text Spaces</option>
                        <option value="short_answer">📝 Short Theoretical Written Paragraph</option>
                        <option value="workout">🧮 Practical Problem Solving Workout Exercise</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Question Body Prompt text</label>
                    <textarea v-model="form.question_text" rows="3" class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none" required></textarea>
                </div>

                <!-- 4 Options Field Blocks Grid Container -->
                <div v-if="form.question_type === 'multiple_choice'" class="grid grid-cols-2 gap-4">
                    <div><label class="font-bold text-slate-600 block mb-1">Choice A</label><input v-model="form.option_a" class="bg-slate-50 border p-2.5 rounded-lg w-full focus:outline-none" required /></div>
                    <div><label class="font-bold text-slate-600 block mb-1">Choice B</label><input v-model="form.option_b" class="bg-slate-50 border p-2.5 rounded-lg w-full focus:outline-none" required /></div>
                    <div><label class="font-bold text-slate-600 block mb-1">Choice C</label><input v-model="form.option_c" class="bg-slate-50 border p-2.5 rounded-lg w-full focus:outline-none" required /></div>
                    <div><label class="font-bold text-slate-600 block mb-1">Choice D</label><input v-model="form.option_d" class="bg-slate-50 border p-2.5 rounded-lg w-full focus:outline-none" required /></div>
                </div>

                <div v-if="form.question_type === 'true_false'" class="grid grid-cols-2 gap-4">
                    <div><label class="font-bold text-slate-600 block mb-1">Option A</label><input type="text" placeholder="True" class="bg-slate-100 border p-2.5 rounded-lg w-full font-bold" disabled /></div>
                    <div><label class="font-bold text-slate-600 block mb-1">Option B</label><input type="text" placeholder="False" class="bg-slate-100 border p-2.5 rounded-lg w-full font-bold" disabled /></div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Target Correct Answer Key Benchmark</label>
                    <input v-model="form.correct_option" type="text" placeholder="e.g. A, True, or grading rule text copies..." class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none font-mono font-bold" required />
                </div>

                <div class="flex items-center justify-between pt-2">
                    <Link :href="route('teacher.courses.quizzes', course.id)" class="text-slate-500 font-bold underline">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="bg-slate-900 text-white font-bold px-5 py-2.5 rounded-xl uppercase tracking-wider">Save Target Question Entry</button>
                </div>
            </form>
        </div>
    </TeacherSidebarLayout>
</template>