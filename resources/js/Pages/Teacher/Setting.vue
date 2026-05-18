<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

// UI Presentation States
const isDarkMode = ref(false);
const showDeleteConfirm = ref(false);

// Form 1: Identity Record Parameters
const profileForm = useForm({
    name: props.auth?.user?.name || '',
    email: props.auth?.user?.email || '',
});

// Form 2: Security Credentials Matrix
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Form 3: Account Lifecycle Termination
const deletionForm = useForm({
    password: '',
});

// Submit Actuators
const updateProfile = () => {
    profileForm.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

const destroyAccount = () => {
    deletionForm.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            deletionForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Engine Profile Control" />

    <div 
        :class="[isDarkMode ? 'bg-[#0b111e] text-slate-100' : 'bg-[#f8fafc] text-slate-800']" 
        class="min-h-screen transition-colors duration-300 font-sans antialiased selection:bg-slate-900 selection:text-white"
    >
        <!-- ═════════════════════════════════════════════════════════════
             PREMIUM APPLICATION HEADER DOCK BAR
             ═════════════════════════════════════════════════════════════ -->
        <header 
            :class="[isDarkMode ? 'bg-[#111927] border-slate-800/80 shadow-slate-950/20' : 'bg-white border-slate-200/80 shadow-slate-100/60']" 
            class="border-b px-8 py-5 sticky top-0 z-30 shadow-sm"
        >
            <div class="max-w-5xl mx-auto flex justify-between items-center">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <span class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-mono font-bold px-2 py-0.5 rounded text-[9px] uppercase tracking-wider border border-indigo-100/60 dark:border-indigo-900/30">
                            Identity Node
                        </span>
                    </div>
                    <h1 class="text-base font-black tracking-tight uppercase mt-1">Developer Account Profile</h1>
                </div>

                <button 
                    @click="isDarkMode = !isDarkMode" 
                    type="button"
                    class="px-3.5 py-2 rounded-xl border font-bold text-[10px] uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-2 shadow-sm"
                    :class="[isDarkMode ? 'bg-slate-800 border-slate-700 text-amber-400' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50']"
                >
                    <span>{{ isDarkMode ? '☀️ LIGHT' : '🌙 DARK' }}</span>
                </button>
            </div>
        </header>

        <!-- ═════════════════════════════════════════════════════════════
             MAIN PROFILE GRID ARCHITECTURE
             ═════════════════════════════════════════════════════════════ -->
        <main class="max-w-5xl mx-auto p-8 space-y-10 text-xs">
            
            <!-- SECTION 1: PROFILE INFORMATION CORE RECORD -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="space-y-1.5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-500 font-mono">Profile Information</h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                        Update your account's core profile identification strings and transmission electronic mail routing addresses.
                    </p>
                </div>

                <div :class="[isDarkMode ? 'bg-[#111927] border-slate-800/80' : 'bg-white border-slate-200/80']" class="md:col-span-2 border rounded-2xl p-6 shadow-sm space-y-5">
                    <form @submit.prevent="updateProfile" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">Display Identity Name</label>
                            <input 
                                v-model="profileForm.name" 
                                type="text" 
                                :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white focus:bg-[#222e42]' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white']"
                                class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-inner" 
                                required 
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">Electronic Email Address</label>
                            <input 
                                v-model="profileForm.email" 
                                type="email" 
                                :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white focus:bg-[#222e42]' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white']"
                                class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-inner" 
                                required 
                            />
                        </div>

                        <div class="flex justify-end pt-2">
                            <button 
                                type="submit" 
                                :disabled="profileForm.processing"
                                class="bg-slate-900 hover:bg-slate-950 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-wider text-[10px] transition-all disabled:opacity-40 cursor-pointer shadow-sm"
                            >
                                Save Core Parameters
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <hr :class="[isDarkMode ? 'border-slate-800' : 'border-slate-200/60']" />

            <!-- SECTION 2: CRYPTOGRAPHIC SECURITY PASSWORD MATRIX -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="space-y-1.5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-500 font-mono">Update Password</h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                        Ensure your account access sequence is backed by a sufficiently long, high-entropy random key string to maintain local environment boundary isolation.
                    </p>
                </div>

                <div :class="[isDarkMode ? 'bg-[#111927] border-slate-800/80' : 'bg-white border-slate-200/80']" class="md:col-span-2 border rounded-2xl p-6 shadow-sm space-y-5">
                    <form @submit.prevent="updatePassword" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">Current Vault Password</label>
                            <input 
                                v-model="passwordForm.current_password" 
                                type="password" 
                                :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white focus:bg-[#222e42]' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white']"
                                class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-inner" 
                                autocomplete="current-password"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">New Cryptographic Key</label>
                            <input 
                                v-model="passwordForm.password" 
                                type="password" 
                                :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white focus:bg-[#222e42]' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white']"
                                class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-inner" 
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">Confirm Key Match</label>
                            <input 
                                v-model="passwordForm.password_confirmation" 
                                type="password" 
                                :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white focus:bg-[#222e42]' : 'bg-slate-50 border-slate-200 text-slate-900 focus:bg-white']"
                                class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-inner" 
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="flex justify-end pt-2">
                            <button 
                                type="submit" 
                                :disabled="passwordForm.processing"
                                class="bg-slate-900 hover:bg-slate-950 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-wider text-[10px] transition-all disabled:opacity-40 cursor-pointer shadow-sm"
                            >
                                Commit Security Modification
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <hr :class="[isDarkMode ? 'border-slate-800' : 'border-slate-200/60']" />

            <!-- SECTION 3: SYSTEM LIFECYCLE IRREVERSIBLE ACCOUNT DELETION -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="space-y-1.5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-rose-500 font-mono">Irreversible Action Zone</h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                        Purge existence traces completely. Once your node lifecycle is destroyed, all related database records, workspace nodes, and linked resource hashes are permanently deleted.
                    </p>
                </div>

                <div class="md:col-span-2 border border-rose-200/80 dark:border-rose-950/40 bg-rose-50/20 dark:bg-rose-950/5 rounded-2xl p-6 shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase">Destructive Termination Directive</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Before proceeding, confirm you have pulled down all necessary local course configurations or schema records. No rollback logs will be preserved.
                        </p>
                    </div>

                    <div class="flex pt-2">
                        <button 
                            @click="showDeleteConfirm = true"
                            type="button"
                            class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-wider text-[10px] transition-all cursor-pointer shadow-md shadow-rose-600/10"
                        >
                            Execute Node Termination
                        </button>
                    </div>
                </div>
            </section>

        </main>

        <!-- ═════════════════════════════════════════════════════════════
             DESTRUCTIVE VERIFICATION DIALOG MODAL CONTAINER
             ═════════════════════════════════════════════════════════════ -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 animate-fadeIn">
            <div :class="[isDarkMode ? 'bg-[#111927] border-slate-800' : 'bg-white border-slate-200']" class="max-w-md w-full border rounded-2xl p-6 shadow-2xl space-y-4 text-xs animate-scaleUp">
                <div>
                    <h3 class="text-sm font-black uppercase text-rose-600 dark:text-rose-400 tracking-tight">Confirm Irreversible Purge</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-[11px] mt-1 leading-relaxed">
                        Are you completely certain? Please supply your active gate key password to authorize this container deletion task.
                    </p>
                </div>

                <form @submit.prevent="destroyAccount" class="space-y-4">
                    <div class="space-y-1.5">
                        <input 
                            v-model="deletionForm.password" 
                            type="password" 
                            :class="[isDarkMode ? 'bg-[#1d283a] border-slate-700 text-white' : 'bg-slate-50 border-slate-200 text-slate-900']"
                            class="w-full rounded-xl p-3 text-xs transition-all border font-medium focus:outline-none focus:ring-1 focus:ring-rose-500" 
                            placeholder="Enter account password"
                            required 
                        />
                    </div>

                    <div class="flex justify-end gap-3 pt-1">
                        <button 
                            @click="showDeleteConfirm = false" 
                            type="button"
                            :class="[isDarkMode ? 'bg-slate-800 text-slate-300 border-slate-700' : 'bg-slate-100 text-slate-600 border-slate-200']"
                            class="px-4 py-2 border rounded-xl font-bold uppercase tracking-wider text-[10px] cursor-pointer"
                        >
                            Abort Task
                        </button>
                        <button 
                            type="submit" 
                            :disabled="deletionForm.processing"
                            class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl font-bold uppercase tracking-wider text-[10px] transition-all disabled:opacity-40 cursor-pointer"
                        >
                            Confirm Purge
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<style scoped>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes scaleUp { from { opacity: 0; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }
.animate-fadeIn { animation: fadeIn 0.2s ease forwards; }
.animate-scaleUp { animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>