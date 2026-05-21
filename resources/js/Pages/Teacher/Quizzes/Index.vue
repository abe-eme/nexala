<script setup>
import { Head, Link } from '@inertiajs/vue3';

// FIXED: Added attempts array to defineProps so Vue can access the backend data safely
defineProps({
    course: Object,
    quizzes: Array,
    attempts: Array
});
</script>

<template>
    <Head :title="`Quiz Management - ${course.title}`" />

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Actions Layout -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Quiz Management Workspace
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Course: <span class="font-medium text-gray-700">{{ course.title }}</span>
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <Link 
                    :href="route('teacher.courses.quizzes.create', course.id)"
                    class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Create Evaluation Question
                </Link>
            </div>
        </div>

        <div class="space-y-10">
            <!-- SECTOR A: ACTIVE QUIZ QUESTIONS -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Active Question Banks</h3>
                </div>
                <ul role="list" class="divide-y divide-gray-200">
                    <li v-for="quiz in quizzes" :key="quiz.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">{{ quiz.question_text }}</p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Type: {{ quiz.question_type }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex text-xs text-gray-500 space-x-4">
                                <p><strong>Correct Target:</strong> Option {{ quiz.correct_option.toUpperCase() }}</p>
                            </div>
                        </div>
                    </li>
                    <li v-if="quizzes.length === 0" class="text-center py-8 text-sm text-gray-500">
                        No quiz items built for this curriculum track yet.
                    </li>
                </ul>
            </div>

            <!-- SECTOR B: REAL-TIME QUIZ SCORE SUBMISSIONS LOG -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg bg-white">
                            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Student Performance Streams</h3>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Profile</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evaluation Grade</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="attempt in attempts" :key="attempt.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-left">
                                            {{ attempt.student_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                            {{ attempt.student_email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-left">
                                            <span class="px-2.5 py-1 text-xs font-bold rounded bg-green-100 text-green-800">
                                                {{ attempt.score }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                            {{ attempt.created_at ? new Date(attempt.created_at).toLocaleDateString() : 'N/A' }}
                                        </td>
                                    </tr>
                                    <!-- Safe length confirmation hook handles empty arrays smoothly -->
                                    <tr v-if="attempts.length === 0">
                                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 bg-gray-50">
                                            No automated student grading runs tracked inside this course room yet.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>