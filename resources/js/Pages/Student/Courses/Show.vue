<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    isEnrolled: Boolean,
    lessons: Array,
    allLessonsCompleted: Boolean,
    quizzes: Array,
    assignments: Array,
    enrollmentDetails: Object
});

const enroll = () => { router.post(route('student.courses.enroll', props.course.id)); };
const unenroll = () => { router.post(route('student.courses.unenroll', props.course.id)); };
const markComplete = (lessonId) => { router.post(route('student.lessons.complete', lessonId)); };
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-8 max-w-6xl mx-auto space-y-8">
            <!-- Header Status Panel -->
            <div class="p-8 rounded-2xl bg-slate-900 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 shadow-xl border border-slate-800">
                <div>
                    <span class="text-[10px] font-bold px-2.5 py-1 bg-indigo-500 text-white rounded-md uppercase tracking-widest">{{ course.category }}</span>
                    <h1 class="text-2xl font-black tracking-tight mt-3">{{ course.title }}</h1>
                    <p class="text-slate-400 mt-1 text-xs">Instructor: {{ course.teacher?.name }}</p>
                </div>
                
                <div class="shrink-0">
                    <button v-if="!isEnrolled" @click="enroll" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-3 rounded-xl transition shadow-lg">Enroll in Course</button>
                    <button v-else @click="unenroll" class="bg-rose-600/10 hover:bg-rose-600 hover:text-white text-rose-400 text-xs font-bold px-4 py-2.5 rounded-xl transition border border-rose-500/20">Cancel Enrollment</button>
                </div>
            </div>

            <!-- Suspended Gateway View For Unenrolled Users -->
            <div v-if="!isEnrolled" class="p-12 text-center bg-white border border-dashed rounded-2xl space-y-3">
                <p class="text-slate-700 font-bold text-sm">Classroom Locked</p>
                <p class="text-xs text-slate-400 max-w-md mx-auto leading-relaxed">Enroll in this course above to track your progress, take quizzes, and earn your official certificate.</p>
            </div>

            <!-- Enrolled Active Workspace -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Main Section: Course Lessons -->
                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">1. Course Lessons</h3>
                    
                    <div v-for="(lesson, index) in lessons" :key="lesson.id" 
                         :class="[!lesson.is_unlocked ? 'opacity-40 bg-slate-50 border-slate-100 cursor-not-allowed select-none' : 'bg-white shadow-sm border border-slate-200']"
                         class="p-5 rounded-xl flex justify-between items-center transition-all">
                        <div class="pr-4">
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">Lesson 0{{ index + 1 }}</span>
                            <h4 class="font-bold text-slate-800 text-sm mt-0.5 leading-snug">{{ lesson.title }}</h4>
                        </div>

                        <div class="shrink-0">
                            <!-- Locked Indicator -->
                            <span v-if="!lesson.is_unlocked" class="text-[10px] text-slate-400 bg-slate-100 px-2.5 py-1 rounded font-bold border">Locked</span>
                            
                            <!-- Open Active Module Actions -->
                            <div v-else-if="lesson.is_unlocked && !lesson.is_completed" class="flex items-center space-x-4">
                                <Link :href="route('student.lessons.show', [course.id, lesson.id])" class="text-xs font-bold text-indigo-600 hover:underline">View Lesson</Link>
                                <button @click="markComplete(lesson.id)" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold px-3 py-2 rounded-lg transition shadow-sm">Mark Complete</button>
                            </div>

                            <!-- Verified Resolution Check -->
                            <span v-else class="text-[10px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded font-extrabold flex items-center gap-1">✓ Completed</span>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Component Section: Capstones & Certificates -->
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Course Final Tasks</h3>

                    <!-- State Box: Lessons Incomplete -->
                    <div v-if="!allLessonsCompleted" class="p-6 bg-slate-50 border border-dashed rounded-xl text-center space-y-2">
                        <p class="text-xs font-bold text-slate-600">Final Tasks Locked</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed">Please complete all available lessons to unlock your final project workspace and review quiz.</p>
                    </div>

                    <!-- State Box: Capstones Open and Unlocked -->
                    <div v-else class="space-y-4">
                        <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-sm space-y-4">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Required Final Tasks</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Click a task to open its dedicated workspace window.</p>
                            </div>
                            
                            <div class="divide-y text-xs text-slate-700">
                                <!-- Quiz Routing Navigation -->
                                <div class="py-4 flex justify-between items-center">
                                    <div>
                                        <span class="font-bold block text-slate-800">Final Review Quiz</span>
                                        <span class="text-[10px] text-rose-500 block mt-0.5 font-medium">⚠️ Anti-Cheat Protection Enabled</span>
                                    </div>
                                    <Link :href="route('student.quizzes.show', course.id)" class="bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition">
                                        Start Quiz
                                    </Link>
                                </div>
                                
                                <!-- Assignment File Upload Navigation -->
                                <div class="py-4 flex justify-between items-center">
                                    <div>
                                        <span class="font-bold block text-slate-800">Project Assignment</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Supports PDF or Word documents</span>
                                    </div>
                                    <Link :href="route('student.assignments.show', course.id)" class="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold px-3 py-2 rounded-lg shadow-sm transition">
                                        Open Upload Form
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Certification State Monitor -->
                        <div class="p-5 bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-xl shadow-md space-y-4 border border-slate-800">
                            <div>
                                <span class="text-[9px] text-indigo-300 font-mono uppercase tracking-widest block">Course Status</span>
                                <h4 class="font-bold text-sm text-slate-100 mt-1">Official Course Certificate</h4>
                            </div>

                            <div v-if="enrollmentDetails?.certificate_status === 'pending'" class="p-3 bg-white/5 rounded-lg text-[11px] text-slate-400 border border-white/5 leading-relaxed">
                                Your submissions are currently being verified by your instructor. Once approved, you can download your certificate here.
                            </div>

                            <div v-else-if="enrollmentDetails?.certificate_status === 'issued'" class="space-y-3">
                                <div class="text-xs text-emerald-400 font-mono font-bold">✓ Verified Final Grade: {{ enrollmentDetails.final_grade }}%</div>
                                <a :href="route('student.certificate.download', course.id)" target="_blank" class="block w-full text-center bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-lg text-xs font-bold transition shadow-md">
                                    Download Certificate
                                </a>
                            </div>

                            <div v-else class="text-[11px] text-slate-400 leading-relaxed">
                                Your certificate will generate automatically as soon as all final tests and written works are completed and approved.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>