<script setup>
import { ref, onMounted } from 'vue';
import { Link, Head } from '@inertiajs/vue3';

defineProps({
    title: String
});

// Sidebar & Theme Persistent States
const isSidebarExpanded = ref(true);
const isDarkMode = ref(false);

// Toggle Actuators
const toggleSidebar = () => {
    isSidebarExpanded.value = !isSidebarExpanded.value;
};

const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    localStorage.setItem('student-theme', isDarkMode.value ? 'dark' : 'light');
};

// Sync theme preference on component mount
onMounted(() => {
    const savedTheme = localStorage.getItem('student-theme');
    if (savedTheme === 'dark') {
        isDarkMode.value = true;
    }
});
</script>

<template>
    <div :class="[isDarkMode ? 'bg-[#0b111e] text-slate-100' : 'bg-[#f8fafc] text-slate-800']" class="min-h-screen flex transition-colors duration-200 font-sans antialiased selection:bg-indigo-600 selection:text-white">
        <Head :title="title ? `${title} | Nexus Learn` : 'Nexus Learn Student'" />

        <!-- ═════════════════════════════════════════════════════════════
             PERSISTENT COLLAPSIBLE NAVIGATION SIDEBAR
             ═════════════════════════════════════════════════════════════ -->
        <aside 
            :class="[
                isSidebarExpanded ? 'w-64' : 'w-20',
                isDarkMode ? 'bg-[#111927] border-slate-800' : 'bg-white border-slate-200/80'
            ]"
            class="hidden md:flex flex-col border-r h-screen sticky top-0 transition-all duration-300 z-40 p-4 justify-between"
        >
            <div class="space-y-6">
                <!-- Branding Header Box -->
                <div class="flex items-center gap-3 px-2 h-10 overflow-hidden">
                    <span class="bg-indigo-600 text-white font-mono font-black p-2 rounded-xl text-xs tracking-wider shadow-md shadow-indigo-600/20">NL</span>
                    <h2 v-show="isSidebarExpanded" class="font-black text-xs uppercase tracking-wider origin-left transition-all duration-200">
                        Nexus Learn
                    </h2>
                </div>

                <!-- Navigation Route Pipelines -->
                <nav class="space-y-1.5">
                    <Link 
                        :href="route('student.dashboard')"
                        :class="[route().current('student.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'hover:bg-slate-500/10']"
                        class="flex items-center gap-3.5 p-3 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all"
                    >
                        <span class="text-xs">📊</span>
                        <span v-show="isSidebarExpanded" class="origin-left transition-all duration-200">Dashboard</span>
                    </Link>

                    <Link 
                        :href="route('student.courses.catalog')"
                        :class="[route().current('student.courses.catalog') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'hover:bg-slate-500/10']"
                        class="flex items-center gap-3.5 p-3 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all"
                    >
                        <span class="text-xs">🌐</span>
                        <span v-show="isSidebarExpanded" class="origin-left transition-all duration-200">Explore Courses</span>
                    </Link>
                </nav>
            </div>

            <!-- Footer Meta Area within Sidebar -->
            <div v-show="isSidebarExpanded" class="p-2 border-t border-dashed" :class="[isDarkMode ? 'border-slate-800' : 'border-slate-200/60']">
                <p class="text-[9px] font-mono text-slate-400 font-bold uppercase tracking-widest">Self-Paced Track v1.0</p>
            </div>
        </aside>

        <!-- ═════════════════════════════════════════════════════════════
             MAIN VIEWPORT CONTAINER & TOP NAV BAR ACTION MATRIX
             ═════════════════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <header 
                :class="[isDarkMode ? 'bg-[#111927]/80 border-slate-800/80 shadow-slate-950/10' : 'bg-white/80 border-slate-200/80 shadow-slate-100/40']" 
                class="border-b px-6 py-4 sticky top-0 z-30 shadow-sm backdrop-blur-md flex justify-between items-center h-20 transition-colors duration-200"
            >
                <!-- Hamburger Action Button Toggle -->
                <button 
                    @click="toggleSidebar" 
                    type="button" 
                    class="p-2.5 rounded-xl border hover:bg-slate-500/10 transition-all cursor-pointer shadow-xs hidden md:block"
                    :class="[isDarkMode ? 'border-slate-700 text-slate-300' : 'border-slate-200 text-slate-600']"
                >
                    <span class="font-mono font-bold text-xs">☰</span>
                </button>

                <!-- Mobile Header Branding Wrapper -->
                <div class="md:hidden flex items-center gap-2">
                    <span class="bg-indigo-600 text-white font-mono font-bold px-2 py-0.5 rounded text-[10px]">NL</span>
                    <h1 class="font-black text-xs uppercase tracking-wider">Nexus Learn</h1>
                </div>

                <!-- Right-Side App Utility Control Bars -->
                <div class="flex items-center gap-3">
                    <button 
                        @click="toggleTheme" 
                        type="button"
                        class="px-3.5 py-2 rounded-xl border font-bold text-[10px] uppercase tracking-wider transition-all duration-200 cursor-pointer shadow-xs"
                        :class="[isDarkMode ? 'bg-slate-800 border-slate-700 text-amber-400' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50']"
                    >
                        <span>{{ isDarkMode ? '☀️ LIGHT' : '🌙 DARK' }}</span>
                    </button>
                </div>
            </header>

            <!-- ═════════════════════════════════════════════════════════════
                 DYNAMIC CONTENT INJECTION TARGET LAYER (<SLOT />)
                 ═════════════════════════════════════════════════════════════ -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto overflow-x-hidden">
                <slot />
            </main>
        </div>
    </div>
</template>