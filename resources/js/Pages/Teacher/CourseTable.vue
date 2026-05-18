<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({
    auth: Object,
    courses: Array
});
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Course Workspace Management Table" />

        <div class="p-8 max-w-7xl mx-auto mt-4">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Academic Course Catalog</h1>
                    <p class="text-slate-500 text-xs">Manage workspace records, course status states, and educational learning assets.</p>
                </div>
                <Link 
                    v-if="$page.props.auth.user.role === 'teacher'"
                    :href="route('teacher.courses.create')" 
                    class="bg-slate-900 hover:bg-black text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all uppercase tracking-wider"
                >
                    ➕ Create Course Profile
                </Link>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="p-4 pl-6">Course Profile Title</th>
                            <th class="p-4">Category Subject</th>
                            <th class="p-4">Approve Status</th>
                            <th class="p-4 text-right pr-6">Management Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        <tr v-if="courses.length === 0">
                            <td colspan="4" class="p-12 text-center text-slate-400 italic">No courses active inside this listing profile layout view.</td>
                        </tr>
                        <tr v-for="course in courses" :key="course.id" class="hover:bg-slate-50/40 transition-colors">
                            <td class="p-4 pl-6 font-bold text-slate-900">
                                <div>{{ course.title }}</div>
                                
                                <!-- 🔒 CONTROLS DISPLAYED ONLY IF TEACHER ROLE MATED -->
                                <div v-if="$page.props.auth.user.role === 'teacher'" class="mt-1 space-x-3 text-[11px] font-normal">
                                    <Link :href="route('teacher.courses.edit', course.id)" class="text-amber-600 hover:text-amber-700 font-bold underline">
                                        ⚙️ Edit Details
                                    </Link>
                                    <Link :href="route('teacher.courses.destroy', course.id)" method="delete" as="button" class="text-rose-600 hover:text-rose-700 font-bold underline cursor-pointer">
                                        🗑️ Delete Profile
                                    </Link>
                                </div>
                                
                                <!-- 🎓 COMPLIANCE DISPLAYED ONLY IF STUDENT ROLE MATED -->
                                <div v-if="$page.props.auth.user.role === 'student'" class="mt-1 text-[11px] text-emerald-600 font-medium">
                                    ⭐ Final Attained Course Grade: {{ course.pivot?.grade || 'Evaluation Pending' }}
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-500">{{ course.category }}</td>
                            <td class="p-4">
                                <span :class="course.status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'" class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded border">
                                    {{ course.status }}
                                </span>
                            </td>
                            <td class="p-4 text-right pr-6 space-x-1.5">
                                <Link :href="route('teacher.courses.show', course.id)" class="bg-slate-900 hover:bg-black text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors inline-block">
                                    📖 Lessons Table
                                </Link>
                                <Link :href="route('teacher.courses.quizzes', course.id)" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors inline-block border border-emerald-200/40">
                                    ❓ Course Quiz
                                </Link>
                                <Link :href="route('teacher.courses.assignments', course.id)" class="bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors inline-block border border-blue-200/40">
                                    💼 Course Assignment
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>