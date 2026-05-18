<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import TeacherSidebarLayout from '@/Layouts/TeacherSidebarLayout.vue';

const props = defineProps({
    auth: Object,
    course: Object,
    availableLessons: Array
});

// Setup form state matching your exact schema structures
const form = useForm({
    title: '',
    media_type: 'text',
    text_content: '',
    prerequisite_lesson_id: '',
    file_payload: null
});

// AI Engine loading states
const isGenerating = ref(false);
const aiError = ref('');

// Inline AI Engine Autocomplete content generator
const generateLessonContentWithAI = async () => {
    if (!form.title) {
        aiError.value = "⚠️ Please fill in the Lesson Title first. The AI needs the title to generate specific module study guides.";
        return;
    }

    aiError.value = '';
    isGenerating.value = true;

    try {
        const response = await axios.post(route('teacher.lessons.ai.generate'), {
            title: form.title
        });

        if (response.data && response.data.content) {
            form.text_content = response.data.content;
        }
    } catch (error) {
        console.error("AI Lesson Generation Failed:", error);
        aiError.value = "❌ Could not contact AI generator. Please verify your web route mapping.";
    } finally {
        isGenerating.value = false;
    }
};

const submitLessonModule = () => {
    form.post(route('teacher.lessons.store', props.course.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <TeacherSidebarLayout :auth="auth">
        <Head title="Create New Lesson Module" />
        
        <div class="p-8 max-w-4xl mx-auto text-xs mt-4">
            
            <!-- 1. HEADER BREADCRUMB NAVIGATION -->
            <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <Link :href="route('teacher.courses.show', course.id)" class="text-emerald-600 hover:text-emerald-700 transition-colors font-bold inline-flex items-center gap-1">
                        ← Back to Lessons Directory Table
                    </Link>
                    <h1 class="text-xl font-black text-slate-900 uppercase tracking-wide mt-2">
                        📖 Create Lesson Module Unit
                    </h1>
                    <p class="text-slate-500 mt-1 text-[11px]">
                        Adding structural learning components to: <span class="font-bold text-slate-700">{{ course.title }}</span>
                    </p>
                </div>
            </div>

            <!-- 2. STYLED FORM WORKSPACE CONTROLLER -->
            <form @submit.prevent="submitLessonModule" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                
                <!-- SECTION 1: CORE MODULE CONFIGURATION DETAILS -->
                <div class="p-6 space-y-5">
                    <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">
                        01. Module Configuration Parameters
                    </h2>

                    <!-- Main Title Input Field -->
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Lesson Module Title <span class="text-red-500">*</span>
                        </label>
                        <input 
                            v-model="form.title" 
                            type="text" 
                            placeholder="e.g., Understanding Controller Routing Context Pipelines"
                            class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none font-bold text-slate-800 focus:border-slate-400 focus:bg-white transition-all text-xs" 
                            required 
                        />
                        <div v-if="form.errors.title" class="text-red-500 font-bold mt-1">{{ form.errors.title }}</div>
                    </div>

                    <!-- Side-by-Side Metadata Grid Elements -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Media Delivery Type Selector -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Primary Media Delivery Type
                            </label>
                            <select 
                                v-model="form.media_type"
                                class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none font-bold text-slate-700 focus:border-slate-400 focus:bg-white transition-all text-xs"
                            >
                                <option value="text">📄 Read Study Text Document</option>
                                <option value="video">🎥 MP4 Video Presentation Media</option>
                                <option value="audio">🎵 Audio Voice Narration File</option>
                                <option value="image">🖼️ Graphic Infographic Blueprint</option>
                            </select>
                        </div>

                        <!-- Roadmap Prerequisite Matrix Unlocking Element -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Prerequisite Completion Barrier (Optional)
                            </label>
                            <select 
                                v-model="form.prerequisite_lesson_id"
                                class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none font-bold text-slate-700 focus:border-slate-400 focus:bg-white transition-all text-xs"
                            >
                                <option value="">No Prerequisite - Available Immediately</option>
                                <option v-for="item in availableLessons" :key="item.id" :value="item.id">
                                    🔓 Must complete: {{ item.title }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Structural File Payload Loader Drop-zone -->
                    <div v-if="form.media_type !== 'text'" class="bg-slate-50 border border-dashed border-slate-200 p-4 rounded-xl transition-all animate-fadeIn">
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Attach Media File Asset Payload <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="file" 
                            @input="form.file_payload = $event.target.files[0]"
                            class="w-full bg-white border border-slate-200 p-2.5 rounded-lg focus:outline-none font-bold text-slate-600 focus:border-slate-400 text-xs file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-900 file:text-white hover:file:bg-black file:cursor-pointer"
                            required
                        />
                        <div v-if="form.errors.file_payload" class="text-red-500 font-bold mt-1">{{ form.errors.file_payload }}</div>
                    </div>
                </div>

                <!-- SECTION 2: INTEGRATED MARKDOWN LECTURE WORKSPACE -->
                <div class="p-6 bg-slate-50/50 border-t border-slate-200 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-2">
                        <h2 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                            02. Lecture Material Study Blueprint
                        </h2>

                        <!-- Inline AI Copywriting Injection Tool Trigger -->
                        <button 
                            @click="generateLessonContentWithAI"
                            type="button"
                            :disabled="isGenerating"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-3 py-1.5 rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer disabled:opacity-50 text-[10px] uppercase tracking-wider shadow-sm"
                        >
                            <span>{{ isGenerating ? '⚡ AI Writing Guide...' : '✨ Auto-Write Lesson with AI' }}</span>
                        </button>
                    </div>

                    <!-- Validation Banner Frame -->
                    <div v-if="aiError" class="text-red-600 font-bold bg-red-50 p-3 rounded-xl border border-red-200 text-[11px] animate-fadeIn">
                        {{ aiError }}
                    </div>

                    <!-- Core Textarea Panel Container Layout -->
                    <div class="relative bg-white rounded-xl border border-slate-200 focus-within:border-slate-400 transition-all shadow-inner">
                        <textarea 
                            v-model="form.text_content" 
                            rows="14" 
                            placeholder="# Enter Markdown Technical Headings here...&#10;&#10;Draft up custom technical parameters or execute the autocomplete button helper tool above to programmatically stream a clean course curriculum scaffold instantly."
                            class="w-full bg-transparent border-0 p-4 rounded-xl focus:outline-none leading-relaxed text-slate-700 font-mono text-[11px] min-h-[280px] resize-y" 
                            :class="{ 'opacity-20 pointer-events-none': isGenerating }"
                        ></textarea>

                        <!-- Loading overlay veil element -->
                        <div v-if="isGenerating" class="absolute inset-0 flex items-center justify-center bg-slate-50/40 backdrop-blur-[1px] rounded-xl">
                            <div class="bg-slate-900 border border-slate-800 text-white font-bold px-4 py-2.5 rounded-xl uppercase tracking-widest text-[10px] shadow-lg flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                Engine Compiling Study Matrix Parameters...
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="form.errors.text_content" class="text-red-500 font-bold mt-1">{{ form.errors.text_content }}</div>
                </div>

                <!-- SECTION 3: PANEL ACTIONS EXECUTION FOOTER BAR -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between px-6">
                    <Link :href="route('teacher.courses.show', course.id)" class="text-slate-500 font-bold hover:text-slate-700 transition-colors uppercase tracking-wider text-[11px]">
                        Discard Draft
                    </Link>
                    
                    <button 
                        type="submit" 
                        :disabled="form.processing" 
                        class="bg-slate-900 hover:bg-black text-white font-black px-6 py-2.5 rounded-xl uppercase tracking-widest transition-all disabled:opacity-50 text-[11px] shadow-sm cursor-pointer"
                    >
                        {{ form.processing ? 'Persisting Content Asset...' : 'Publish Lesson Module' }}
                    </button>
                </div>

            </form>
        </div>
    </TeacherSidebarLayout>
</template>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out forwards;
}
</style>