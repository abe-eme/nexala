<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex h-screen bg-slate-50 overflow-hidden">
        <!-- Structural Navigation Sidebar Panel -->
        <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col z-20 shrink-0">
            <!-- Branding Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800">
                <span class="text-lg font-black tracking-wider text-white">NEXUS<span class="text-indigo-400">LEARN</span></span>
            </div>

            <!-- Main Route Navigation Nodes -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <Link :href="route('student.dashboard')" 
                      :class="[route().current('student.dashboard') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                      class="flex items-center px-4 py-3 rounded-xl transition-all text-sm group">
                    <span>Performance Dashboard</span>
                </Link>

                <Link :href="route('student.courses.index')" 
                      :class="[route().current('student.courses.*') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white']"
                      class="flex items-center px-4 py-3 rounded-xl transition-all text-sm group">
                    <span>Available Classrooms</span>
                </Link>
            </nav>

            <!-- User Context Footer Card -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center space-x-3">
                    <div class="h-9 w-9 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-sm uppercase">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-slate-500 truncate capitalize">{{ $page.props.auth.user.role }} Account</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Primary View Display Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
                <slot />
            </main>
        </div>
    </div>
</template>