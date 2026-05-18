<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    auth: Object
});
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex font-sans antialiased text-slate-800">
        <!-- FIXED SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between fixed h-full shadow-xl z-50">
            <div>
                <!-- Teacher Profile Status Header -->
                <div class="p-6 bg-slate-950 border-b border-slate-800 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center font-bold text-lg text-white">
                        {{ auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="truncate">
                        <h2 class="font-bold text-sm tracking-wide truncate">{{ auth.user.name }}</h2>
                        <span class="text-[11px] text-emerald-400 font-semibold uppercase tracking-wider">Verified Instructor</span>
                    </div>
                </div>

                <!-- Easy Navigation Menu Options -->
                <nav class="p-4 space-y-2">
                    <Link :href="route('teacher.dashboard')" :class="route().current('teacher.dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-150">
                        <span>📊</span> <span>Main Dashboard</span>
                    </Link>
                    <Link :href="route('teacher.courses.index')" :class="route().current('teacher.courses.*') ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-150">
                        <span>📚</span> <span>My Courses</span>
                    </Link>
                </nav>
            </div>

            <!-- Profile Account Settings Switcher -->
            <div class="p-4 bg-slate-950 border-t border-slate-800 space-y-2">
                <Link :href="route('teacher.profile')" class="flex items-center justify-center space-x-2 bg-slate-800 hover:bg-slate-700 text-white py-2.5 rounded-xl text-xs font-bold transition-colors w-full">
                    <span>⚙️</span> <span>Profile Settings</span>
                </Link>
                <div class="text-[10px] text-center text-slate-500 font-medium">Nexus Learn Platform • Free Plan</div>
            </div>
        </aside>

        <!-- MAIN MAIN APP CONTENT WORKSPACE PANEL -->
        <div class="flex-1 pl-64">
            <slot />
        </div>
    </div>
</template>