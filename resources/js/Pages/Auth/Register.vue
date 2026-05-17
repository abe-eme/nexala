<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { AlertTriangle, User, GraduationCap, ShieldCheck } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'student', 
    invitation_code: '',
});

const setRole = (selectedRole) => {
    form.role = selectedRole;
    if (selectedRole === 'student') {
        form.invitation_code = ''; 
    }
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create Profile" />

        <div class="mb-6 text-center">
            <h1 class="text-3xl font-black text-white tracking-tight">Create Profile</h1>
            <p class="text-sm text-slate-300 mt-2">Choose your account classification to get started.</p>
        </div>

        <div v-if="Object.keys(form.errors).length > 0" class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl flex items-start space-x-3 text-sm text-red-200">
            <AlertTriangle class="w-5 h-5 text-red-400 shrink-0 mt-0.5" />
            <div>
                <span class="block font-bold text-red-300 mb-1">Registration Gate Blocked</span>
                <span v-for="(error, key) in form.errors" :key="key" class="block">• {{ error }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <button 
                type="button"
                @click="setRole('student')"
                class="p-4 rounded-xl border-2 transition-all text-center flex flex-col items-center justify-center space-y-2 select-none"
                :class="form.role === 'student' ? 'bg-indigo-600/20 border-indigo-500 text-white shadow-lg' : 'bg-slate-800/40 border-slate-700 text-slate-400 hover:border-slate-600'"
            >
                <User class="w-5 h-5" :class="form.role === 'student' ? 'text-indigo-400' : 'text-slate-500'" />
                <span class="text-sm font-bold">I am a Student</span>
            </button>

            <button 
                type="button"
                @click="setRole('teacher')"
                class="p-4 rounded-xl border-2 transition-all text-center flex flex-col items-center justify-center space-y-2 select-none"
                :class="form.role === 'teacher' ? 'bg-indigo-600/20 border-indigo-500 text-white shadow-lg' : 'bg-slate-800/40 border-slate-700 text-slate-400 hover:border-slate-600'"
            >
                <GraduationCap class="w-5 h-5" :class="form.role === 'teacher' ? 'text-indigo-400' : 'text-slate-500'" />
                <span class="text-sm font-bold">I am a Teacher</span>
            </button>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="p-3 bg-slate-800/60 border border-slate-700/60 rounded-xl text-center text-xs text-slate-300 font-semibold">
                Registering account status as: <span class="text-indigo-400 uppercase font-black ml-1">{{ form.role }}</span>
            </div>

            <div v-if="form.role === 'teacher'" class="p-4 bg-indigo-900/30 border border-indigo-500/30 rounded-xl space-y-2">
                <div class="flex items-center space-x-2 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                    <ShieldCheck class="w-4 h-4 text-indigo-400" />
                    <label for="invitation_code">Teacher Invitation Key</label>
                </div>
                <input
                    id="invitation_code"
                    type="text"
                    class="block w-full bg-slate-900 border-2 border-indigo-500/40 text-white rounded-lg focus:border-indigo-500 text-sm p-3 h-11 tracking-widest font-mono"
                    v-model="form.invitation_code"
                    required
                    placeholder="ENTER-TEACHER-CODE"
                />
            </div>

            <div>
                <label for="name" class="block text-sm font-bold text-white mb-2">Display Name</label>
                <input
                    id="name"
                    type="text"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.name"
                    required
                    autofocus
                    placeholder="First and last name"
                />
            </div>

            <div>
                <label for="email" class="block text-sm font-bold text-white mb-2">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.email"
                    required
                    placeholder="name@example.com"
                />
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-white mb-2">Create Password</label>
                <input
                    id="password"
                    type="password"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.password"
                    required
                    placeholder="Min. 8 characters + numbers"
                />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-white mb-2">Confirm Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.password_confirmation"
                    required
                    placeholder="Repeat password exactly..."
                />
            </div>

            <div class="pt-3">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30" :disabled="form.processing">
                    <span v-if="form.processing">Verifying Registration...</span>
                    <span v-else>Complete Profile Build</span>
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-800 text-sm text-slate-300">
                Already have an account? 
                <Link :href="route('login')" class="text-cyan-400 font-bold hover:underline ml-1">Log In Here &rarr;</Link>
            </div>
        </form>
    </GuestLayout>
</template>