<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    auth: Object,
    course: Object,
    lessons: Array
});

const form = useForm({});
const selectedLesson = ref(null);

const deleteLessonStep = (lessonId) => {
    if (confirm('Are you completely sure you want to permanently delete this lesson module from the course schema?')) {
        form.delete(route('teacher.lessons.destroy', { lesson: lessonId }), {
            onSuccess: () => {
                selectedLesson.value = null;
            }
        });
    }
};

const viewLessonContents = (lesson) => {
    selectedLesson.value = lesson;
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head :title="`Lessons - ${course.title}`" />

        <div class="p-8 max-w-7xl mx-auto mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN: Module Inventory Directory Grid -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <Link :href="route('teacher.courses.index')" class="text-xs font-bold text-emerald-600 hover:underline">
                            ← Back to Courses Master Grid
                        </Link>
                        <h1 class="text-xl font-black text-slate-900 mt-1">{{ course.title }} — Lesson Outline Modules</h1>
                    </div>
                    <Link :href="route('teacher.lessons.create', { course: course.id })" class="bg-slate-900 hover:bg-black text-white text-xs font-bold px-4 py-2 rounded-xl">
                        ➕ Add Lesson Step
                    </Link>
                </div>

                <div class="bg-white border rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="p-4 pl-6">Module Title</th>
                                <th class="p-4">Format Type</th>
                                <th class="p-4 text-right pr-6">Management Control Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            <tr v-if="lessons.length === 0">
                                <td colspan="3" class="p-12 text-center text-slate-400 italic">No instructional step lesson segments found for this course listing mapping.</td>
                            </tr>
                            <tr v-for="lesson in lessons" :key="lesson.id" class="hover:bg-slate-50/40">
                                <td class="p-4 pl-6 font-bold text-slate-900">{{ lesson.title }}</td>
                                <td class="p-4">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] uppercase font-mono border">
                                        {{ lesson.media_type }}
                                    </span>
                                </td>
                                <td class="p-4 text-right pr-6 space-x-3">
                                    <button @click="viewLessonContents(lesson)" class="text-emerald-600 hover:underline font-bold cursor-pointer">👁️ View Content</button>
                                    <button @click="deleteLessonStep(lesson.id)" class="text-rose-600 hover:underline font-bold cursor-pointer">🗑️ Drop</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT COLUMN: Interactive Asset Content Inspector Box -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 min-h-[450px]">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 border-b pb-2 text-slate-400">📄 File Content Display Inspector</h3>
                
                <div v-if="selectedLesson" class="space-y-4">
                    <div>
                        <h2 class="text-base font-black text-slate-900">{{ selectedLesson.title }}</h2>
                        <span class="text-[10px] bg-slate-900 text-white font-mono uppercase px-2 py-0.5 rounded mt-1 inline-block">Format Target: {{ selectedLesson.media_type }}</span>
                    </div>

                    <!-- Media Path Attachment Link Checker -->
                    <div v-if="selectedLesson.media_path" class="p-4 bg-white border border-slate-200 rounded-xl">
                        <span class="font-bold block text-slate-700 text-[11px] mb-2">📁 Attached Media Asset File Link:</span>
                        <a :href="`/storage/${selectedLesson.media_path}`" target="_blank" class="text-blue-600 underline font-semibold break-all text-xs">
                            Open Uploaded {{ selectedLesson.media_type }} Asset File Link →
                        </a>
                    </div>

                    <!-- Reading Text Block Body Container -->
                    <div class="space-y-1">
                        <span class="font-bold text-slate-700 text-[11px] block">Written Text Content / Body Descriptions:</span>
                        <div class="p-4 bg-white border border-slate-200 rounded-xl text-slate-600 text-xs leading-relaxed max-h-[300px] overflow-y-auto whitespace-pre-wrap">
                            {{ selectedLesson.text_content || 'No text description chapters provided for this lesson module module.' }}
                        </div>
                    </div>
                </div>

                <div v-else class="text-center text-slate-400 italic pt-36 text-xs">
                    Click "👁️ View Content" on any line item list row to display files and layout lecture text copy parameters here.
                </div>
            </div>

        </div>
    </TeacherSidebarLayout>
</template>