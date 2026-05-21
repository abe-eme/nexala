<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    assignment: Object,
    errors: Object // Add this line to map out backend structural error logs
});

const form = useForm({
    submission_payload: '',
    file_payload: null
});

const submitAssignment = () => {
    // FIXED: Ensured absolute dot routing naming configuration is fully declared
    form.post(route('student.assignments.submit', { course: props.course.id }), {
        forceFormData: true,
        onSuccess: () => {
            console.log('Submission uploaded successfully.');
        },
        onError: (err) => {
            console.error('Validation parameters rejected package:', err);
        }
    });
};
</script>

<template>
    <Head :title="'Assignment: ' + course.title" />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Assignment Task Upload Terminal</h1>
                <p class="text-gray-600 mb-6">Course Framework Instance: <span class="font-semibold">{{ course.title }}</span></p>

                <!-- ERROR DEBUG DISPLAY GRID (Will print out why Laravel is blocking the payload) -->
                <div v-if="Object.keys(errors).length > 0" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded text-sm">
                    <p class="font-bold mb-1">Please resolve the following submission issues:</p>
                    <ul>
                        <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                    </ul>
                </div>

                <div v-if="assignment" class="bg-slate-50 border border-slate-200 rounded p-4 mb-6">
                    <h3 class="font-semibold text-lg text-slate-800 mb-1">{{ assignment.title }}</h3>
                    <p class="text-slate-600 whitespace-pre-wrap text-sm">{{ assignment.instructions }}</p>
                </div>

                <form @submit.prevent="submitAssignment" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Workspace Notes / Links</label>
                        <textarea v-model="form.submission_payload" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Paste git workspace repository URLs or inline implementation logs here..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Source Package (.zip, .pdf, .docx)</label>
                        <!-- FIXED: Ensured the file tracker updates the form payload correctly -->
                        <input type="file" @change="form.file_payload = $event.target.files[0]" class="w-full block border border-gray-300 rounded shadow-sm text-sm p-2 text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full h-2 mt-2 rounded overflow-hidden">
                            {{ form.progress.percentage }}%
                        </progress>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="w-full sm:w-auto px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded shadow transition disabled:opacity-50">
                            {{ form.processing ? 'Uploading Package...' : 'Upload Final Project Artifacts' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>