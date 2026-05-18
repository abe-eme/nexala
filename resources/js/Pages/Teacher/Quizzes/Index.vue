<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({ auth: Object, course: Object, quizzes: Array });
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Course Examination Question Directory" />
        <div class="p-8 max-w-4xl mx-auto text-xs mt-4 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <Link :href="route('teacher.courses.index')" class="text-emerald-600 hover:underline font-bold">← Back</Link>
                    <h1 class="text-xl font-black text-slate-900 mt-1">{{ course.title }} — Active Quiz Bank Repository</h1>
                </div>
                <Link :href="route('teacher.courses.quizzes.create', course.id)" class="bg-slate-900 hover:bg-black text-white font-bold px-4 py-2 rounded-xl">
                    ➕ Create Question Entry
                </Link>
            </div>

            <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
                <div v-if="quizzes.length === 0" class="p-12 text-center text-slate-400 italic">No custom examination test question entries found mapped to this timeline profile yet.</div>
                <div v-else class="divide-y divide-slate-100">
                    <div v-for="(quiz, index) in quizzes" :key="quiz.id" class="p-5 hover:bg-slate-50/50 transition-all">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="bg-slate-900 text-white text-[10px] px-1.5 py-0.5 font-mono font-bold rounded">Q#{{ index + 1 }}</span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-mono px-2 py-0.5 rounded uppercase border font-bold">Format: {{ quiz.question_type }}</span>
                            </div>
                            <p class="text-slate-900 font-bold text-sm leading-relaxed">{{ quiz.question_text }}</p>
                            
                            <div v-if="quiz.question_type === 'multiple_choice'" class="grid grid-cols-2 gap-2 text-[11px] text-slate-500 font-medium pl-1">
                                <span>🔴 Option A: {{ quiz.option_a }}</span><span>🔵 Option B: {{ quiz.option_b }}</span>
                                <span>🟡 Option C: {{ quiz.option_c || 'Empty' }}</span><span>🟢 Option D: {{ quiz.option_d || 'Empty' }}</span>
                            </div>
                            <div class="bg-emerald-50 text-emerald-700 font-mono px-2 py-1 rounded inline-block font-bold border border-emerald-200/50">🎯 Correct Value: "{{ quiz.correct_option }}"</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>