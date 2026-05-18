<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

const props = defineProps({
    auth: Object,
    course: Object
});

// Setup the Inertia form state matching your database structure
const form = useForm({
    title: '',
    instructions: ''
});

// Local layout UI states
const isGenerating = ref(false);
const aiError = ref('');

// Inline AI generator function that inserts directly into description text box
const generateAssignmentContentWithAI = async () => {
    if (!form.title) {
        aiError.value = "⚠️ Please type a project title first! The AI needs a title to generate relevant assignment content.";
        return;
    }

    aiError.value = '';
    isGenerating.value = true;

    try {
        const response = await axios.post(route('teacher.assignments.ai.generate'), {
            title: form.title,
            topic: 'Comprehensive Course Application Modules'
        });

        if (response.data && response.data.instructions) {
            // Write the AI content directly into the form's instruction description
            form.instructions = response.data.instructions;
        }
    } catch (error) {
        console.error("AI Generation Failed:", error);
        aiError.value = "❌ Could not generate content. Please verify your routing setup.";
    } finally {
        isGenerating.value = false;
    }
};

const saveAssignment = () => {
    form.post(route('teacher.courses.assignments.store', props.course.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Create Course Assignment Tasks" />
        
        <div class="p-8 max-w-3xl mx-auto text-xs mt-8">
            <div class="bg-white border p-6 rounded-2xl shadow-sm space-y-6">
                
                <!-- HEADER NAVIGATION -->
                <div>
                    <Link :href="route('teacher.courses.assignments', course.id)" class="text-emerald-600 hover:underline font-bold">
                        ← Back to Assignments Inventory
                    </Link>
                    <h1 class="text-lg font-black text-slate-900 uppercase tracking-wide mt-2">
                        💼 Create New Course Assignment
                    </h1>
                    <p class="text-slate-500 mt-0.5">
                        Course Target: <span class="font-bold text-slate-700">{{ course.title }}</span>
                    </p>
                </div>

                <hr class="border-slate-100" />
                
                <!-- MAIN FORM WORKSPACE -->
                <form @submit.prevent="saveAssignment" class="space-y-5">
                    
                    <!-- 1. Assignment Title -->
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 tracking-wider">Assignment Project Title</label>
                        <input 
                            v-model="form.title" 
                            type="text" 
                            placeholder="e.g., Building a Functional Full-Stack Admin Hub"
                            class="w-full bg-slate-50 border p-3 rounded-xl focus:outline-none font-bold text-slate-800 focus:border-slate-400" 
                            required 
                        />
                        <div v-if="form.errors.title" class="text-red-500 font-bold mt-1">{{ form.errors.title }}</div>
                    </div>

                    <!-- 2. Assignment Instructions with Inline AI Trigger Tool -->
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                            <label class="block font-bold text-slate-700 uppercase tracking-wider">
                                Assignment Content & Instructions Description
                            </label>
                            
                            <!-- Inline AI Helper Action Trigger -->
                            <button 
                                @click="generateAssignmentContentWithAI"
                                type="button"
                                :disabled="isGenerating"
                                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-black px-3 py-1 rounded-lg border border-emerald-200 transition-colors flex items-center gap-1 cursor-pointer disabled:opacity-50"
                            >
                                <span>{{ isGenerating ? '✨ Writing Content...' : '✨ Write Assignment Content with AI' }}</span>
                            </button>
                        </div>

                        <!-- Feedback validation banner if title is blank -->
                        <div v-if="aiError" class="mb-2 text-red-600 font-bold bg-red-50 p-2 rounded-lg border border-red-200">
                            {{ aiError }}
                        </div>

                        <!-- Textarea Content Workspace Box -->
                        <div class="relative">
                            <textarea 
                                v-model="form.instructions" 
                                rows="12" 
                                placeholder="Type your project steps manually, or click the AI button above to auto-write a structural template layout directly into this box..."
                                class="w-full bg-slate-50 border p-4 rounded-xl focus:outline-none leading-relaxed text-slate-700 focus:border-slate-400 font-mono text-[11px] transition-all" 
                                :class="{ 'opacity-40 pointer-events-none': isGenerating }"
                                required
                            ></textarea>

                            <!-- Absolute position indicator overlay during active generation updates -->
                            <div v-if="isGenerating" class="absolute inset-0 flex items-center justify-center bg-slate-100/10 backdrop-blur-[1px] rounded-xl">
                                <div class="bg-slate-900 text-white font-bold px-4 py-2 rounded-xl uppercase tracking-widest shadow-md animate-pulse">
                                    AI Engine Processing...
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="form.errors.instructions" class="text-red-500 font-bold mt-1">{{ form.errors.instructions }}</div>
                    </div>

                    <!-- 3. Form Submission Buttons Row Layout -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <Link :href="route('teacher.courses.assignments', course.id)" class="text-slate-500 font-bold hover:text-slate-700 transition-colors">
                            Cancel Changes
                        </Link>
                        
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="bg-slate-900 hover:bg-black text-white font-bold px-6 py-2.5 rounded-xl uppercase tracking-wider transition-all disabled:opacity-50"
                        >
                            {{ form.processing ? 'Publishing Content...' : 'Publish Assignment Project' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </TeacherSidebarLayout>
</template>