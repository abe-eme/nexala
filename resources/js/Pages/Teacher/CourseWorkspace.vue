<script setup>
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

const props = defineProps({
    auth: Object,
    course: Object,
    lessons: Array,
    quizzes: Array,
    grades: Array
});

// Clean tab sub-navigation for native users (Removed the standalone AI tab!)
const currentSection = ref('lessons'); // Options: 'lessons', 'quizzes', 'grades'

// Form for adding a lesson (with inline AI generation support)
const lessonForm = useForm({
    title: '',
    text_content: '',
    media_type: 'none', // text, video, audio, image
    uploaded_file: null,
});

// Form for creating quizzes with automated suggestion capability
const quizForm = useForm({
    question_text: '',
    option_a: '',
    option_b: '',
    correct_option: 'A'
});

// Simple toggle states to show helper placeholder suggestions
const visualAILoading = ref(false);

// Functions to trigger the inline helpers
const triggerInlineLessonHelper = () => {
    if(!lessonForm.title) {
        alert('Please type a lesson title first so the helper knows what to write about!');
        return;
    }
    visualAILoading.value = true;
    // Simulating instant helper text generation
    setTimeout(() => {
        lessonForm.text_content = `Here is a beginner-friendly reading draft about "${lessonForm.title}":\n\n1. Introduction to the topic.\n2. Core concept breakdown with simple everyday terms.\n3. Quick review questions for the students.`;
        visualAILoading.value = false;
    }, 800);
};

const triggerInlineQuizHelper = () => {
    visualAILoading.value = true;
    setTimeout(() => {
        quizForm.question_text = `True or False: Is studying "${props.course.title}" helpful for daily activities?`;
        quizForm.option_a = "True";
        quizForm.option_b = "False";
        visualAILoading.value = false;
    }, 800);
};

const saveLesson = () => {
    lessonForm.post(route('teacher.lessons.store', props.course.id), {
        onSuccess: () => lessonForm.reset()
    });
};

const saveQuiz = () => {
    quizForm.post(route('teacher.quizzes.store', props.course.id), {
        onSuccess: () => quizForm.reset()
    });
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head :title="`Workspace - ${course.title}`" />

        <div class="p-8 max-w-6xl mx-auto space-y-6">
            
            <!-- HEADER HUB CARD -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div>
                    <Link :href="route('teacher.courses.index')" class="text-xs font-bold text-slate-400 hover:text-emerald-600 transition-colors uppercase tracking-wider block mb-1">
                        ← Back to My Courses Table
                    </Link>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ course.title }} Workspace</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Subject Topic: <span class="font-bold text-slate-700">{{ course.category }}</span></p>
                </div>

                <!-- SIMPLE TABS -->
                <div class="flex gap-2 bg-slate-100 p-1.5 rounded-xl">
                    <button @click="currentSection = 'lessons'" :class="currentSection === 'lessons' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        📖 Lessons List
                    </button>
                    <button @click="currentSection = 'quizzes'" :class="currentSection === 'quizzes' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        ❓ Quizzes & Assignments
                    </button>
                    <button @click="currentSection = 'grades'" :class="currentSection === 'grades' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">
                        🎓 Grades & Completion
                    </button>
                </div>
            </div>

            <!-- ================= WORKSPACE TAB 1: LESSON CREATOR & LIST ================= -->
            <div v-if="currentSection === 'lessons'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left 2 Columns: Clear Lesson List Directory -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 text-xs text-blue-800">
                        🎬 <strong>Netflix Progress Mode Active:</strong> System saves video and audio timestamps automatically so naive students can resume exactly where they left off.
                    </div>

                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lessons in this Course</h3>

                    <div v-if="lessons.length === 0" class="bg-white p-12 rounded-2xl border border-dashed text-center text-sm text-slate-400 italic">
                        No lessons added yet. Use the tool on the right to start building your class material step-by-step.
                    </div>

                    <div v-else class="space-y-2">
                        <div v-for="(lesson, index) in lessons" :key="lesson.id" class="bg-white border border-slate-200 p-4 rounded-xl flex justify-between items-center shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">{{ lesson.title }}</h4>
                                    <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-500 uppercase font-bold tracking-wide">
                                        {{ lesson.media_type }} Format
                                    </span>
                                </div>
                            </div>

                            <!-- Sequential Rules Notification -->
                            <div>
                                <span v-if="index === 0" class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded">
                                    🔓 Starter Lesson
                                </span>
                                <span v-else class="text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-1 rounded">
                                    🔒 Requires Lesson {{ index }} Completion
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Simple Inline Manual Creator Form -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 h-fit">
                    <div class="border-b pb-2 flex justify-between items-center">
                        <h3 class="font-bold text-sm text-slate-900">Add New Lesson</h3>
                        <button type="button" @click="triggerInlineLessonHelper" class="text-[11px] font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded-lg transition-colors">
                            ✨ Help Me Write This
                        </button>
                    </div>

                    <form @submit.prevent="saveLesson" class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Lesson Title</label>
                            <input v-model="lessonForm.title" type="text" placeholder="e.g., Understanding Basic Vocabulary" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-500" required />
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Upload Media Format</label>
                            <select v-model="lessonForm.media_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none">
                                <option value="none">📖 Written Text / Document only</option>
                                <option value="video">🎥 Video File upload</option>
                                <option value="audio">🎵 Audio Voice file upload</option>
                                <option value="image">🖼️ Picture / Diagram Graphic</option>
                            </select>
                        </div>

                        <div v-if="lessonForm.media_type !== 'none'" class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <input type="file" @input="lessonForm.uploaded_file = $event.target.files[0]" class="text-xs w-full" />
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Lesson Text Content</label>
                            <textarea v-model="lessonForm.text_content" rows="5" placeholder="Type lesson materials here, or click 'Help Me Write This' at the top to draft details instantly..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-500"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white text-xs font-bold py-2.5 rounded-xl uppercase tracking-wider transition-colors">
                            Save Lesson Component
                        </button>
                    </form>
                </div>
            </div>

            <!-- ================= WORKSPACE TAB 2: QUIZZES & ASSIGNMENTS ================= -->
            <div v-if="currentSection === 'quizzes'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Side List -->
                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Assignments & Quizzes</h3>
                    
                    <div v-if="quizzes.length === 0" class="bg-white p-12 rounded-2xl border border-dashed text-center text-sm text-slate-400 italic">
                        No quiz items built for this course. Fill out the helper block on the right to start tracking user milestones.
                    </div>
                    
                    <div v-else class="space-y-3">
                        <div v-for="(quiz, idx) in quizzes" :key="quiz.id" class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm">
                            <span class="text-xs font-bold text-emerald-600">Question Item #{{ idx + 1 }}</span>
                            <h4 class="font-bold text-sm text-slate-900 mt-1">{{ quiz.question_text }}</h4>
                            <div class="grid grid-cols-2 gap-2 mt-3 text-xs text-slate-600 bg-slate-50 p-2 rounded-lg">
                                <p :class="quiz.correct_option === 'A' ? 'text-emerald-600 font-bold' : ''">Choice A: {{ quiz.option_a }}</p>
                                <p :class="quiz.correct_option === 'B' ? 'text-emerald-600 font-bold' : ''">Choice B: {{ quiz.option_b }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Form with integrated suggestion generator button -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 h-fit">
                    <div class="border-b pb-2 flex justify-between items-center">
                        <h3 class="font-bold text-sm text-slate-900">Create a Task</h3>
                        <button type="button" @click="triggerInlineQuizHelper" class="text-[11px] font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded-lg transition-colors">
                            ✨ Suggest Questions for Me
                        </button>
                    </div>

                    <form @submit.prevent="saveQuiz" class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Question / Assignment Prompt</label>
                            <input v-model="quizForm.question_text" type="text" placeholder="Type a task prompt or question..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Option A Choice</label>
                            <input v-model="quizForm.option_a" type="text" placeholder="First option response text" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Option B Choice</label>
                            <input v-model="quizForm.option_b" type="text" placeholder="Second option response text" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Correct Answer Selector</label>
                            <select v-model="quizForm.correct_option" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs focus:outline-none">
                                <option value="A">Option A is the Correct Answer</option>
                                <option value="B">Option B is the Correct Answer</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-bold text-xs py-2.5 rounded-xl uppercase transition-colors shadow-sm">
                            Save Quiz Question
                        </button>
                    </form>
                </div>
            </div>

            <!-- ================= WORKSPACE TAB 3: GRADES & SCORE CARDS ================= -->
            <div v-if="currentSection === 'grades'" class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Student Course Metrics</h3>
                
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                <th class="p-4 pl-6">Student Name</th>
                                <th class="p-4">Completed Milestones</th>
                                <th class="p-4">Quiz Score Average</th>
                                <th class="p-4 text-right pr-6">Certification Level</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <tr v-if="grades.length === 0">
                                <td colspan="4" class="p-12 text-center text-slate-400 italic">
                                    No students have completed custom evaluation milestones in this section yet.
                                </td>
                            </tr>
                            <tr v-for="grade in grades" :key="grade.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 pl-6 font-bold text-slate-900">{{ grade.student_name }}</td>
                                <td class="p-4 text-slate-500 font-mono text-xs">{{ grade.lessons_completed }} Lessons Read</td>
                                <td class="p-4 font-bold text-xs font-mono text-emerald-600">{{ grade.average_score }}% Mark</td>
                                <td class="p-4 text-right pr-6">
                                    <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded">
                                        Passed Course
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </TeacherSidebarLayout>
</template>