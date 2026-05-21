<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    course: Object,
    quizzes: Array
});

const form = useForm({
    answers: {}
});

const submitQuiz = () => {
    form.post(route('student.quizzes.submit', props.course.id));
};
</script>

<template>
    <Head :title="'Quiz: ' + course.title" />

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ course.title }} - Evaluation Assessment</h1>
                <p class="text-gray-600 mb-6">Please complete all available module conceptual review questions below.</p>

                <form @submit.prevent="submitQuiz" class="space-y-6">
                    <div v-if="quizzes.length === 0" class="text-gray-500 py-4 text-center">
                        No quiz questions are assigned to this dynamic module layout parameters yet.
                    </div>

                    <div v-for="(quiz, index) in quizzes" :key="quiz.id" class="border-b pb-4 last:border-0">
                        <p class="font-medium text-gray-800 mb-2">{{ index + 1 }}. {{ quiz.question_text }}</p>
                        
                        <div class="space-y-2 mt-2">
                            <label v-if="quiz.option_a" class="flex items-center space-x-3">
                                <input type="radio" :name="'question-' + quiz.id" value="A" v-model="form.answers[quiz.id]" class="rounded text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-gray-700">A) {{ quiz.option_a }}</span>
                            </label>
                            <label v-if="quiz.option_b" class="flex items-center space-x-3">
                                <input type="radio" :name="'question-' + quiz.id" value="B" v-model="form.answers[quiz.id]" class="rounded text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-gray-700">B) {{ quiz.option_b }}</span>
                            </label>
                            <label v-if="quiz.option_c" class="flex items-center space-x-3">
                                <input type="radio" :name="'question-' + quiz.id" value="C" v-model="form.answers[quiz.id]" class="rounded text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-gray-700">C) {{ quiz.option_c }}</span>
                            </label>
                            <label v-if="quiz.option_d" class="flex items-center space-x-3">
                                <input type="radio" :name="'question-' + quiz.id" value="D" v-model="form.answers[quiz.id]" class="rounded text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-gray-700">D) {{ quiz.option_d }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="form.processing" class="w-full sm:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded shadow transition disabled:opacity-50">
                            Submit Final Exam Sheet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>