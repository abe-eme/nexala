<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    auth: Object
});

// Sidebar State: True = Expanded, False = Shrunk (Icons Only)
const isSidebarExpanded = ref(true);

// Action Dropdowns
const showNotifications = ref(false);
const showProfileMenu = ref(false);

// Active Feature Flags
const features = ref({
    aiHelper: true,
    autoGrading: false
});

// Canvas Trend Chart Render Engine
const trendChart = ref(null);
onMounted(() => {
    const ctx = trendChart.value?.getContext('2d');
    if (ctx) {
        ctx.clearRect(0, 0, 500, 160);
        
        // Background Grid Matrix
        ctx.strokeStyle = 'rgba(226, 232, 240, 0.6)';
        ctx.lineWidth = 1;
        for (let i = 20; i < 160; i += 40) {
            ctx.beginPath();
            ctx.moveTo(0, i);
            ctx.lineTo(500, i);
            ctx.stroke();
        }

        // Beautiful Smooth Green Metric Line
        ctx.strokeStyle = '#10b981';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(10, 130);
        ctx.bezierCurveTo(100, 120, 150, 50, 250, 70);
        ctx.bezierCurveTo(320, 90, 400, 30, 480, 40);
        ctx.stroke();
        
        // Soft Area Gradient
        ctx.lineTo(480, 160);
        ctx.lineTo(10, 160);
        ctx.closePath();
        ctx.fillStyle = 'rgba(16, 185, 129, 0.04)';
        ctx.fill();
    }
});
</script>

<template>
    <Head title="Teacher Dashboard" />

    <div class="min-h-screen bg-slate-50 text-slate-900 flex font-sans antialiased">
        
        <!-- ═════════════════════════════════════════════════════════════
             BLACK COLLAPSIBLE SIDEBAR (EXPANDS & SHRINKS WITH HAMBURGER)
             ═════════════════════════════════════════════════════════════ -->
        <aside 
            :class="isSidebarExpanded ? 'w-64' : 'w-20'" 
            class="bg-slate-950 text-slate-200 flex flex-col justify-between transition-all duration-300 ease-in-out z-30 shrink-0 shadow-xl"
        >
            <div>
                <!-- App Title Area -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 overflow-hidden">
                    <div class="flex items-center gap-3 min-w-[200px]">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 flex items-center justify-center text-slate-950 font-black text-sm shrink-0">
                            N
                        </div>
                        <span v-if="isSidebarExpanded" class="font-bold text-sm tracking-wide text-white transition-opacity duration-200">
                            Nexus Learn
                        </span>
                    </div>
                </div>

                <!-- Navigation Sidebar Actions -->
                <nav class="p-3 space-y-1">
                    <Link :href="route('teacher.dashboard')" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-slate-900 text-white font-bold transition-all">
                        <span class="text-base">📊</span>
                        <span v-if="isSidebarExpanded" class="text-xs transition-opacity duration-200">Dashboard</span>
                    </Link>

                    <Link :href="route('teacher.courses.index')" class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-900 hover:text-white font-medium transition-all">
                        <span class="text-base">📁</span>
                        <span v-if="isSidebarExpanded" class="text-xs transition-opacity duration-200">My Courses</span>
                    </Link>
                </nav>
            </div>

            <!-- Profile Info Footer Segment -->
            <div class="p-4 border-t border-slate-800 overflow-hidden bg-slate-950">
                <div class="flex items-center gap-3 min-w-[200px]">
                    <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold text-xs shrink-0 border border-slate-700">
                        {{ auth.user.name[0] }}
                    </div>
                    <div v-if="isSidebarExpanded" class="transition-opacity duration-200">
                        <div class="font-bold text-xs text-slate-200 truncate max-w-[140px]">{{ auth.user.name }}</div>
                        <div class="text-[10px] text-slate-500 capitalize">{{ auth.user.role }} Account</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ═════════════════════════════════════════════════════════════
             MAIN DASHBOARD CONTENT AREA
             ═════════════════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- HEADER APP BAR (FEATURING THE THREE LINE HAMBURGER) -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shrink-0 sticky top-0 z-20">
                
                <div class="flex items-center gap-4">
                    <!-- THREE LINE HAMBURGER BUTTON TOGGLE -->
                    <button 
                        @click="isSidebarExpanded = !isSidebarExpanded" 
                        type="button"
                        class="w-9 h-9 flex flex-col items-center justify-center gap-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-all cursor-pointer"
                    >
                        <span class="w-4 h-0.5 bg-slate-600 transition-all duration-200" :class="{'rotate-45 translate-y-2': !isSidebarExpanded}"></span>
                        <span class="w-4 h-0.5 bg-slate-600 transition-all duration-200" :class="{'opacity-0': !isSidebarExpanded}"></span>
                        <span class="w-4 h-0.5 bg-slate-600 transition-all duration-200" :class="{'-rotate-45 -translate-y-2': !isSidebarExpanded}"></span>
                    </button>

                    <h2 class="text-sm font-bold text-slate-800">Control Panel Overview</h2>
                </div>

                <div class="flex items-center gap-3">
                    
                    <!-- Notification Alerts Dropdown Menu Trigger -->
                    <div class="relative">
                        <button 
                            @click="showNotifications = !showNotifications; showProfileMenu = false"
                            class="px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-2 cursor-pointer transition-colors"
                        >
                            <span>🔔 System Alerts</span>
                            <span class="bg-rose-500 text-white font-bold px-1.5 py-0.5 rounded-full text-[9px]">1</span>
                        </button>

                        <div v-if="showNotifications" class="absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-2 text-xs">
                            <div class="p-2 font-bold text-slate-400 uppercase text-[9px] tracking-wider border-b border-slate-100">Inbox Notifications</div>
                            <div class="p-3 hover:bg-slate-50 rounded-lg mt-1 transition-colors">
                                <p class="font-bold text-slate-800">Welcome Back</p>
                                <p class="text-slate-500 mt-0.5 text-[11px]">System connected to local database workspace setup successfully.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Profile Droplist Trigger -->
                    <div class="relative">
                        <button 
                            @click="showProfileMenu = !showProfileMenu; showNotifications = false"
                            class="px-3 py-2 bg-slate-900 hover:bg-black text-white rounded-xl font-bold text-xs flex items-center gap-1.5 cursor-pointer transition-colors"
                        >
                            <span>Settings Menu</span>
                            <span>⚙️</span>
                        </button>

                        <div v-if="showProfileMenu" class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1.5 text-xs">
                            <Link :href="route('profile.edit')" class="block p-2 rounded-lg font-semibold hover:bg-slate-50 text-slate-700 transition-colors">
                                🔧 Profile Credentials
                            </Link>
                            <hr class="border-slate-100 my-1" />
                            <Link :href="route('logout')" method="post" as="button" class="w-full text-left block p-2 rounded-lg font-bold hover:bg-rose-50 text-rose-600 transition-colors cursor-pointer">
                                🚪 System Logout
                            </Link>
                        </div>
                    </div>

                </div>
            </header>

            <!-- CONTAINER MAIN GRID WRAPPER WITH GRADIENT WORKSPACE ACCENTS -->
            <main class="p-8 max-w-7xl w-full mx-auto space-y-6 text-xs relative">
                
                <!-- Core Status Metric Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm relative overflow-hidden">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catalog Statistics</div>
                        <div class="text-xl font-black mt-1 text-slate-900">Active Learning Environment</div>
                        <p class="text-slate-500 mt-1 text-[11px]">Manage and edit your course modules using the sidebar file catalog navigation.</p>
                    </div>

                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm relative overflow-hidden">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Workspace System Pricing</div>
                        <div class="text-xl font-black mt-1 text-slate-900">Free Open-Source Access</div>
                        <p class="text-slate-500 mt-1 text-[11px]">Your local dashboard configuration parameters are running completely free of charge.</p>
                    </div>

                </div>

                <!-- ANALYTICS AND FEATURE TOGGLES CORE FLEX LAYOUT -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Graph Chart Tracking Box -->
                    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">System Performance Metrics</h3>
                            <p class="text-slate-400 text-[11px] mt-0.5">Real-time attendance metric charts mapping continuous workspace trends.</p>
                            <hr class="border-slate-100 my-4" />
                        </div>

                        <!-- Canvas Target Grid Box Area Component -->
                        <div class="w-full p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-center min-h-[160px]">
                            <canvas ref="trendChart" width="500" height="160" class="w-full max-w-lg max-h-[160px]"></canvas>
                        </div>

                        <div class="flex justify-between text-[10px] text-slate-400 mt-4 font-mono">
                            <span>Initial System State</span>
                            <span>Mid-Term Run</span>
                            <span>Active Runtime Sequence</span>
                        </div>
                    </div>

                    <!-- Side Options Management Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Feature Control Dashboard</h3>
                            <p class="text-slate-400 text-[11px] mt-0.5">Toggle active layout features across your application stack panels instantly.</p>
                            <hr class="border-slate-100 my-4" />
                            
                            <div class="space-y-3">
                                
                                <!-- Toggle Row Option 1 -->
                                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div>
                                        <p class="font-bold text-slate-800">✨ AI Writing Copilot Tool</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Enables AI writing content generation.</p>
                                    </div>
                                    <input 
                                        type="checkbox" 
                                        v-model="features.aiHelper"
                                        class="w-7 h-4 bg-slate-200 checked:bg-slate-900 rounded-full cursor-pointer appearance-none checked:after:translate-x-3.5 after:content-[''] after:absolute after:w-3.5 after:h-3.5 after:bg-white after:rounded-full after:transition-all relative border border-slate-300 top-px"
                                    />
                                </div>

                                <!-- Toggle Row Option 2 -->
                                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div>
                                        <p class="font-bold text-slate-800">🤖 Automated Marking System</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Auto-checks quizzes on save execution.</p>
                                    </div>
                                    <input 
                                        type="checkbox" 
                                        v-model="features.autoGrading"
                                        class="w-7 h-4 bg-slate-200 checked:bg-slate-900 rounded-full cursor-pointer appearance-none checked:after:translate-x-3.5 after:content-[''] after:absolute after:w-3.5 after:h-3.5 after:bg-white after:rounded-full after:transition-all relative border border-slate-300 top-px"
                                    />
                                </div>

                            </div>
                        </div>

                        <!-- Configuration Submission Save Action -->
                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <button 
                                type="button"
                                @click="alert('Workspace feature parameters saved successfully!')"
                                class="w-full bg-slate-900 hover:bg-black text-white font-bold py-2.5 rounded-xl uppercase tracking-wider text-center text-[10px] transition-all cursor-pointer shadow-sm"
                            >
                                Save Feature Updates
                            </button>
                        </div>
                    </div>

                </div>

            </main>
        </div>

    </div>
</template>