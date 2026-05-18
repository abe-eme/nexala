<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'student', 
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col md:flex-row">
        <Head title="Create Your Account | Nexala" />

        <!-- Left Side: Creative Branding Panel -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-700 justify-center items-center p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10 pointer-events-none"></div>
            <div class="relative z-10 max-w-md space-y-4">
                <h1 class="text-5xl font-extrabold tracking-tight">Join Nexala</h1>
                <p class="text-lg text-indigo-100 font-medium">
                    Start deploying courses, tracking live student analytical performance, and managing assignments seamlessly.
                </p>
            </div>
        </div>

        <!-- Right Side: Clean Professional Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Get started</h2>
                    <p class="mt-2 text-sm text-gray-500">Create your structural platform authentication credentials.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                        <div class="mt-1">
                            <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                                class="block w-full px-4 py-2.5 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                placeholder="John Doe" />
                        </div>
                        <p v-if="form.errors.name" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email" type="email" v-model="form.email" required autocomplete="username"
                                class="block w-full px-4 py-2.5 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                placeholder="you@example.com" />
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700">Account Classification Role</label>
                        <div class="mt-1">
                            <select id="role" v-model="form.role" required
                                class="block w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white shadow-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                                <option value="student">I am a Student (Access courses)</option>
                                <option value="teacher">I am an Instructor / Teacher (Create materials)</option>
                            </select>
                        </div>
                        <p v-if="form.errors.role" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.role }}</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                                class="block w-full px-4 py-2.5 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                placeholder="Minimum 8 characters" />
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                                class="block w-full px-4 py-2.5 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                placeholder="••••••••" />
                        </div>
                        <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all transform active:scale-95">
                            Register Base Account
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        Already have an account? 
                        <Link :href="route('login')" class="font-bold text-indigo-600 hover:text-indigo-500 hover:underline">
                            Log back in here
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>