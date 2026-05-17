<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { AlertTriangle, ShieldCheck } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
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
    <GuestLayout>
        <Head title="Secure Log In" />

        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-500/10 mb-3">
                <ShieldCheck class="w-6 h-6 text-indigo-400" />
            </div>
            <h1 class="text-3xl font-black text-white">Secure Access</h1>
            <p class="text-sm text-slate-300 mt-2">Enter credentials to connect to your profile hub.</p>
        </div>

        <div v-if="Object.keys(form.errors).length > 0" class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl flex items-start space-x-3 text-sm text-red-200">
            <AlertTriangle class="w-5 h-5 text-red-400 shrink-0 mt-0.5" />
            <div>
                <span class="block font-bold text-red-300 mb-1">Access Suspended</span>
                <span v-for="(error, key) in form.errors" :key="key" class="block">• {{ error }}</span>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-bold text-white mb-2">Registered Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="name@example.com"
                />
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-bold text-white">Security Password Key</label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-bold text-cyan-400 hover:underline">Forgot Password Key?</Link>
                </div>
                <input
                    id="password"
                    type="password"
                    class="block w-full bg-slate-800/90 border-2 border-slate-700 text-white rounded-xl focus:border-indigo-500 text-base p-3.5 h-12 transition-all placeholder-slate-400"
                    v-model="form.password"
                    required
                    placeholder="••••••••"
                />
            </div>

            <div class="block py-1">
                <label class="flex items-center cursor-pointer select-none">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-2 border-slate-700 bg-slate-800 text-indigo-600 w-5 h-5" />
                    <span class="ms-3 text-sm font-semibold text-slate-200">Remember this hardware session</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30" :disabled="form.processing">
                    <span v-if="form.processing">Processing Security Clearance...</span>
                    <span v-else>Authorize Entry</span>
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-800 text-sm text-slate-300">
                Don't have an account yet? 
                <Link :href="route('register')" class="text-cyan-400 font-bold hover:underline ml-1">Create Account Free &rarr;</Link>
            </div>
        </form>
    </GuestLayout>
</template>