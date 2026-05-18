<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({
    auth: Object,
    course: Object,
    lesson: Object
});
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head :title="lesson.title" />

        <div class="p-8 max-w-3xl mx-auto space-y-6">
            <div>
                <Link :href="route('teacher.courses.show', lesson.course_id)" class="text-xs font-bold text-emerald-600 hover:underline">
                    ← Back to Lessons Table Directory
                </Link>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mt-1">{{ lesson.title }}</h1>
                <p class="text-slate-400 text-xs mt-0.5">Lesson Sequence Order Block: <strong>Step {{ lesson.sort_order }}</strong></p>
            </div>

            <!-- MEDIA FORMAT DISPLAY BLOCK CARD -->
            <div class="bg-slate-900 text-slate-300 p-8 rounded-2xl text-center shadow-inner font-medium text-xs border border-slate-800">
                <span v-if="lesson.media_type === 'text'">📖 Standard Written Document Reader Asset Mode Active</span>
                <span v-else-if="lesson.media_type === 'video'">🎥 Video Player Core Activated (Netflix Stop-Time Resume History Active)</span>
                <span v-else-if="lesson.media_type === 'audio'">🎵 Audio Voice Track Component Ready</span>
                <span v-else>🖼️ Image Reference Diagram Render Frame</span>
            </div>

            <!-- MAIN CONTENTS BOX -->
            <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b pb-1">Lesson Body Text Content</h3>
                <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ lesson.text_content || 'No text material written yet.' }}</p>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>