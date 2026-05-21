<!-- resources/js/Layouts/AdminLayout.vue -->
<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Admin Control Hub'
    },
    metrics: {
        type: Object,
        default: () => ({ pending_teachers: 0 })
    },
    onlineCount: {
        type: Number,
        default: 0
    },
    offlineCount: {
        type: Number,
        default: 0
    }
});

const page = usePage();
const currentAdmin = computed(() => page.props.auth?.user);

// Controls sidebar open/collapsed state layout sizing
const isSidebarExpanded = ref(true);

const handleLogout = () => {
    alert('Terminating session... Redirecting to secure login interface.');
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans flex antialiased">
        <Head :title="`${title} | Nexus Admin`" />

        <!-- 🛠️ SIDEBAR NAV MODULE -->
        <aside 
            :class="isSidebarExpanded ? 'w-64' : 'w-20'" 
            class="bg-slate-900 flex flex-col justify-between z-20 transition-all duration-300 ease-in-out shrink-0 relative border-r border-slate-800 shadow-xl"
        >
            <div>
                <!-- System Branding Header Block -->
                <div class="p-4 border-b border-slate-800 h-20 flex items-center overflow-hidden bg-slate-950/40">
                    <div class="flex items-center space-x-3 w-full min-w-[200px]">
                        <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white text-lg shadow-md shrink-0">
                            Ω
                        </div>
                        <div v-if="isSidebarExpanded" class="overflow-hidden transition-opacity duration-300">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Core Director</p>
                            <p class="text-sm font-bold text-slate-100 truncate tracking-wide">{{ currentAdmin?.name || 'Root Admin' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Application Route Interceptor Anchors -->
                <nav class="p-3 space-y-1">
                    <Link 
                        :href="route('admin.dashboard')" 
                        :class="route().current('admin.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" 
                        class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150"
                    >
                        <span class="text-base w-5 text-center">📊</span> 
                        <span v-if="isSidebarExpanded" class="truncate">Overview Hub</span>
                    </Link>

                    <Link 
                        :href="route('admin.users.index')" 
                        :class="route().current('admin.users.index') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" 
                        class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150"
                    >
                        <span class="text-base w-5 text-center">👥</span> 
                        <span v-if="isSidebarExpanded" class="truncate">User Accounts</span>
                    </Link>
                </nav>
            </div>

            <!-- Identity Session Outro Trigger -->
            <div class="border-t border-slate-800 bg-slate-950/30">
                <button 
                    @click="handleLogout" 
                    class="w-full p-4 flex items-center space-x-3 text-slate-400 hover:bg-rose-950/40 hover:text-rose-400 transition-colors text-sm text-left font-medium"
                >
                    <span class="text-base w-5 text-center">🚪</span>
                    <span v-if="isSidebarExpanded">Secure Sign Out</span>
                </button>
            </div>
        </aside>

        <!-- 🖥 CONTENT INJECTION FRAME -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Global Action Bar -->
            <header class="h-20 border-b border-slate-200 bg-white flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center space-x-4">
                    <button 
                        @click="isSidebarExpanded = !isSidebarExpanded" 
                        class="p-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 transition-colors focus:outline-none"
                    >
                        <span class="text-base font-mono block w-5 h-5 flex items-center justify-center">☰</span>
                    </button>
                    
                    <!-- This slot accepts the text heading delivered from child index panels -->
                    <div>
                        <slot name="header" />
                    </div>
                </div>
                
                <!-- Server Diagnostics Status Telemetry -->
                <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-xl border border-slate-200 text-xs">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-slate-600 font-medium">Online: <strong class="text-slate-900 font-bold">{{ onlineCount }}</strong></span>
                    </div>
                </div>
            </header>

            <!-- Embedded Workspace Body Payload -->
            <main class="p-6 max-w-6xl w-full mx-auto">
                <slot />
            </main>
        </div>
    </div>
</template>