<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    metrics: Object,
    recentCourses: Array,
    chartData: Array
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8 max-w-7xl mx-auto space-y-8">
            <!-- Strategic Status Header -->
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">System Metrics Monitor</h1>
                <p class="text-sm text-slate-500 mt-1">Real-time data aggregation across your active academic fields.</p>
            </div>

            <!-- Numerical Data Metrics Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="p-6 bg-white border rounded-2xl shadow-sm space-y-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Classroom Vectors</p>
                    <p class="text-3xl font-black text-slate-900">{{ metrics.enrolled_count }}</p>
                </div>
                <div class="p-6 bg-white border rounded-2xl shadow-sm space-y-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Completed Syllabus Loops</p>
                    <p class="text-3xl font-black text-emerald-600">{{ metrics.completed_count }}</p>
                </div>
                <div class="p-6 bg-white border rounded-2xl shadow-sm space-y-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Processed Lectures</p>
                    <p class="text-3xl font-black text-indigo-600">{{ metrics.lessons_completed }}</p>
                </div>
            </div>

            <!-- Data Visualizer and Course Tracking Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Ongoing Progression Tracks -->
                <div class="lg:col-span-2 space-y-4">
                    <h2 class="text-lg font-bold text-slate-900">Current Academic Vectors</h2>

                    <div v-if="recentCourses.length === 0" class="p-8 text-center bg-white border rounded-2xl text-slate-400 text-sm">
                        No active course streams recorded. Open the course catalog to initialize tracking.
                    </div>

                    <div v-else v-for="course in recentCourses" :key="course.id" class="p-6 bg-white border rounded-2xl shadow-sm space-y-4 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md uppercase tracking-wider">{{ course.category }}</span>
                                <h3 class="font-bold text-slate-900 text-base mt-2">{{ course.title }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Lead Instructor: {{ course.teacher?.name }}</p>
                            </div>
                            <Link :href="route('student.courses.show', course.id)" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-3 py-2 rounded-xl transition shrink-0">Enter Classroom →</Link>
                        </div>

                        <!-- Progress Bar Component -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-500">Course Syllabus Resolution</span>
                                <span class="text-slate-900">{{ course.progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full transition-all duration-500" :style="{ width: course.progress_percentage + '%' }"></div>
                            </div>
                            <p class="text-[10px] text-slate-400">{{ course.completed_lessons_count }} of {{ course.lessons_count }} dynamic learning locks cleared.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: User Activity Spark Metric -->
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-900">Activity Analytics</h2>
                    <div class="p-6 bg-white border rounded-2xl shadow-sm space-y-4">
                        <p class="text-xs text-slate-500">Historical chart tracking course lesson completion matrices across active user sessions.</p>
                        
                        <!-- Simple Pure CSS Graph Bar Matrix -->
                        <div class="h-32 flex items-end justify-between pt-4 px-2 border-b border-slate-100">
                            <div v-for="day in chartData" :key="day.date" class="w-6 bg-indigo-600/20 rounded-t-sm flex flex-col justify-end group relative transition-all hover:bg-indigo-600" :style="{ height: Math.min((day.count * 20), 100) + '%' }">
                                <div class="absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] py-0.5 px-1.5 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10 font-mono">{{ day.count }} Completed</div>
                            </div>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 font-mono">
                            <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>