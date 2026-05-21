<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    course: {
        type: Object,
        required: true
    },
    lesson: {
        type: Object,
        required: true
    },
    playlist: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <Head :title="lesson.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <Link :href="route('student.courses.show', { course: course.id })" class="hover:text-indigo-600 transition">
                    {{ course.title }}
                </Link>
                <span>/</span>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Active Lecture</span>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Main Lecture Presentation Screen Panel (Takes 2 columns) -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                {{ lesson.title }}
                            </h1>

                            <!-- Media Area: Video Player Layout Option -->
                            <div v-if="lesson.media_type === 'video'" class="aspect-video bg-black rounded-lg mb-6 overflow-hidden flex items-center justify-center text-white">
                                <video v-if="lesson.media_path" :src="`/storage/${lesson.media_path}`" controls class="w-full h-full"></video>
                                <div v-else class="text-gray-400">Video asset file missing or processing</div>
                            </div>

                            <!-- Long-form Text/Markdown Content Panel -->
                            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 space-y-4">
                                <p v-if="lesson.text_content" class="whitespace-pre-wrap">{{ lesson.text_content }}</p>
                                <p v-else class="text-gray-400 italic">No supplemental reading text provided for this learning module.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Curriculum Playlist Sidebar (Takes 1 column) -->
                    <div class="space-y-4">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Course Syllabus</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Track your modular learning progress</p>
                            </div>

                            <div class="divide-y divide-gray-100 dark:divide-gray-700/60 max-h-[600px] overflow-y-auto">
                                <div 
                                    v-for="(item, index) in playlist" 
                                    :key="item.id"
                                    :class="[
                                        'p-4 transition',
                                        item.id === lesson.id 
                                            ? 'bg-indigo-50/70 dark:bg-indigo-950/20 border-l-4 border-indigo-600' 
                                            : 'hover:bg-gray-50 dark:hover:bg-gray-700/30'
                                    ]"
                                >
                                    <!-- FIXED SIDEBAR LINK MAP PASSING BOTH ARGS -->
                                    <Link 
                                        :href="route('student.lessons.show', { course: course.id, lesson: item.id })"
                                        class="block group"
                                    >
                                        <div class="flex items-start space-x-3">
                                            <span :class="[
                                                'w-5 h-5 rounded-full flex items-center justify-center text-xs font-semibold mt-0.5',
                                                item.id === lesson.id 
                                                    ? 'bg-indigo-600 text-white' 
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'
                                            ]">
                                                {{ index + 1 }}
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <p :class="[
                                                    'text-sm font-medium truncate',
                                                    item.id === lesson.id 
                                                        ? 'text-indigo-600 dark:text-indigo-400' 
                                                        : 'text-gray-900 dark:text-gray-100 group-hover:text-indigo-600'
                                                ]">
                                                    {{ item.title }}
                                                </p>
                                                <span class="text-xs text-gray-400 dark:text-gray-500 capitalize">
                                                    {{ item.media_type }} lecture
                                                </span>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>