<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col md:flex-row">
        <Head title="Sign In | Nexala" />

        <!-- Left Side: Creative Branding Panel -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-700 justify-center items-center p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10 pointer-events-none"></div>
            <div class="relative z-10 max-w-md space-y-4">
                <h1 class="text-5xl font-extrabold tracking-tight">Nexala</h1>
                <p class="text-lg text-indigo-100 font-medium">
                    The next-generation smart learning platform for interactive classrooms and modern teaching tools.
                </p>
                <div class="pt-6 flex space-x-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold backdrop-blur-sm">V2.0 Core</span>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold backdrop-blur-sm">Secure Network IP-Gate</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Clean Professional Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Welcome back</h2>
                    <p class="mt-2 text-sm text-gray-500">Access your synchronized system dashboard workspace.</p>
                </div>

                <!-- Status Alerts -->
                <div v-if="status" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-700 shadow-sm animate-fade-in">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                                class="block w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all" 
                                placeholder="name@company.com" />
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 hover:underline">
                                Forgot password?
                            </Link>
                        </div>
                        <div class="mt-1">
                            <input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                                class="block w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                                placeholder="••••••••" />
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-rose-600 font-medium">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" v-model="form.remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded-md transition-all" />
                        <label for="remember" class="ml-2 block text-sm font-medium text-gray-600 select-none">
                            Keep me logged in on this device
                        </label>
                    </div>

                    <div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all transform active:scale-95">
                            Sign In to Account
                        </button>
                    </div>
                </form>

                <!-- Move Link down below the button action element at the bottom -->
                <div class="pt-4 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have an account yet? 
                        <Link :href="route('register')" class="font-bold text-indigo-600 hover:text-indigo-500 hover:underline">
                            Create one here
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>