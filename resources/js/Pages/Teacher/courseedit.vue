<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

// Receives the existing course model data back from your Laravel routing layer
const props = defineProps({
    auth: Object,
    course: Object
});

// Pre-fill form fields using current values from the database row data
const form = useForm({
    title: props.course.title,
    category: props.course.category,
    description: props.course.description || ''
});

// Dispatches a PUT/PATCH update back to your admin resource handler
const updateCourseDetails = () => {
    form.put(route('teacher.courses.update', props.course.id));
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head :title="`Edit - ${course.title}`" />

        <div class="p-8 max-w-2xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm mt-6">
            <div class="mb-6">
                <Link :href="route('teacher.courses.index')" class="text-xs font-bold text-emerald-600 hover:underline">
                    ← Back to Courses Master Directory Table
                </Link>
                <h1 class="text-xl font-black text-slate-900 mt-2">Modify Course Parameters</h1>
                <p class="text-slate-500 text-xs">Update your global catalog definitions, instructional categories, or text profiles below.</p>
            </div>

            <form @submit.prevent="updateCourseDetails" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Course Title</label>
                    <input v-model="form.title" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 font-medium focus:outline-none focus:border-slate-400" required />
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Instructional Subject Category</label>
                    <input v-model="form.category" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 font-medium focus:outline-none focus:border-slate-400" required />
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Comprehensive Description Summary</label>
                    <textarea v-model="form.description" rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 font-medium focus:outline-none focus:border-slate-400 leading-relaxed" placeholder="Write full catalog details here..."></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" :disabled="form.processing" class="w-full bg-slate-900 hover:bg-black text-white font-bold py-3 rounded-xl transition-colors uppercase tracking-wider disabled:opacity-50">
                        {{ form.processing ? 'Saving Changes...' : 'Save Updated Course Profile' }}
                    </button>
                </div>
            </form>
        </div>
    </TeacherSidebarLayout>
</template>