<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

defineProps({
    auth: Object
});

const form = useForm({
    title: '',
    category: '',
    description: ''
});

const submitNewCourseForm = () => {
    // Automatically routes directly back to CourseTable page via index redirection inside Laravel Controller on success
    form.post(route('teacher.courses.store'));
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Create New Course Setup" />

        <div class="p-8 max-w-2xl mx-auto space-y-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create a New Free Course</h1>
                <p class="text-slate-500 text-xs mt-0.5">Provide basic curriculum info. Once submitted, it remains pending until confirmed by admins.</p>
            </div>

            <form @submit.prevent="submitNewCourseForm" class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Course Title</label>
                    <input v-model="form.title" type="text" placeholder="e.g., Simple Language Basics" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:outline-none focus:border-emerald-500 transition-colors" required />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Subject Topic Area / Category</label>
                    <input v-model="form.category" type="text" placeholder="e.g., Basic Languages" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:outline-none focus:border-emerald-500 transition-colors" required />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Brief Content Course Description</label>
                    <textarea v-model="form.description" rows="4" placeholder="Briefly write what your naive users or students will learn..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:outline-none focus:border-emerald-500 transition-colors"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-2 border-t border-slate-100">
                    <Link :href="route('teacher.courses.index')" class="text-sm font-bold text-slate-400 hover:underline px-4 py-2">
                        Cancel & Return
                    </Link>
                    <button type="submit" :disabled="form.processing" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition-colors">
                        Save and Launch
                    </button>
                </div>
            </form>
        </div>
    </TeacherSidebarLayout>
</template>