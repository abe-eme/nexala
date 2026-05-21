<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    metrics: {
        type: Object,
        default: () => ({ total_students: 0, total_teachers: 0, pending_teachers: 0 })
    },
    users: {
        type: Array,
        default: () => []
    },
    courses: {
        type: Array,
        default: () => []
    }
});

const page = usePage();
const currentAdmin = computed(() => page.props.auth?.user);

// Sidebar collapse control
const isSidebarExpanded = ref(true);

// Navigation Workspace Control
const activePage = ref('overview'); 

// Live User Session Telemetry Counts
const onlineCount = computed(() => props.users.filter(u => u.is_online).length);
const offlineCount = computed(() => props.users.filter(u => !u.is_online).length);

// Simple Interactive Demo Chart Data
const monthlySignups = [
    { month: 'Jan', count: 45, percentage: '25%' },
    { month: 'Feb', count: 85, percentage: '45%' },
    { month: 'Mar', count: 60, percentage: '32%' },
    { month: 'Apr', count: 110, percentage: '60%' },
    { month: 'May', count: 180, percentage: '95%' },
];

// Profile Update Form
const profileForm = useForm({
    name: currentAdmin.value?.name || '',
    email: currentAdmin.value?.email || '',
});

const submitProfileUpdate = () => {
    profileForm.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => alert('Profile updated successfully!')
    });
};

// User & Course Status Forms
const userStatusForm = useForm({ status: '' });
const courseStatusForm = useForm({ status: '' });

// Updates user database status and preserves UI scroll position
const updateUserStatus = (userId, newStatus) => {
    userStatusForm.status = newStatus;
    userStatusForm.patch(route('admin.users.status', userId), { preserveScroll: true });
};

// Updates course moderation status
const updateCourseStatus = (courseId, newStatus) => {
    courseStatusForm.status = newStatus;
    courseStatusForm.patch(route('admin.courses.status', courseId), { preserveScroll: true });
};

// Simulated email invite trigger
const sendInviteLink = (teacherEmail) => {
    alert(`Activation onboarding email successfully dispatched to: ${teacherEmail}`);
};

const handleLogout = () => {
    alert('Terminating secure administrator session... Redirecting to portal gateway.');
};

const toggleSidebar = () => {
    isSidebarExpanded.value = !isSidebarExpanded.value;
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans flex antialiased">
        
        <Head title="Enterprise Admin Portal" />

        <!-- 🛠️ SIDEBAR CONTROLLER -->
        <aside 
            :class="isSidebarExpanded ? 'w-64' : 'w-20'" 
            class="bg-slate-900 flex flex-col justify-between z-20 transition-all duration-300 ease-in-out shrink-0 relative border-r border-slate-800 shadow-xl"
        >
            <div>
                <!-- Top Sidebar Header with Logged-in Admin Name -->
                <div class="p-4 border-b border-slate-800 h-20 flex items-center overflow-hidden bg-slate-950/40">
                    <div class="flex items-center space-x-3 w-full min-w-[200px]">
                        <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white text-lg shadow-md shadow-blue-900/50 shrink-0">
                            Ω
                        </div>
                        <div v-if="isSidebarExpanded" class="overflow-hidden transition-opacity duration-300">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Core Director</p>
                            <p class="text-sm font-bold text-slate-100 truncate tracking-wide">{{ currentAdmin?.name || 'Root Administrator' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation List Options -->
                <nav class="p-3 space-y-1">
                    <button @click="activePage = 'overview'" :class="activePage === 'overview' ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150">
                        <span class="text-base w-5 text-center">📊</span> <span v-if="isSidebarExpanded" class="truncate">Overview Hub</span>
                    </button>

                    <button @click="activePage = 'users'" :class="activePage === 'users' ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150">
                        <span class="text-base w-5 text-center">👥</span> <span v-if="isSidebarExpanded" class="truncate">User Accounts</span>
                    </button>

                    <button @click="activePage = 'teacher-onboarding'" :class="activePage === 'teacher-onboarding' ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150 relative">
                        <span class="text-base w-5 text-center">✉️</span> 
                        <span v-if="isSidebarExpanded" class="truncate">Teacher Onboarding</span>
                        <span v-if="(metrics?.pending_teachers ?? 0) > 0" class="absolute right-3 top-1/2 -translate-y-1/2 bg-amber-500 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                            {{ metrics.pending_teachers }}
                        </span>
                    </button>

                    <button @click="activePage = 'courses'" :class="activePage === 'courses' ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150">
                        <span class="text-base w-5 text-center">📚</span> <span v-if="isSidebarExpanded" class="truncate">Course Reviews</span>
                    </button>

                    <button @click="activePage = 'profile'" :class="activePage === 'profile' ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100'" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-left transition-all duration-150">
                        <span class="text-base w-5 text-center">⚙️</span> <span v-if="isSidebarExpanded" class="truncate">Profile Settings</span>
                    </button>
                </nav>
            </div>

            <!-- Session Termination Terminal -->
            <div class="border-t border-slate-800 bg-slate-950/30">
                <button 
                    @click="handleLogout" 
                    class="w-full p-4 flex items-center space-x-3 text-slate-400 hover:bg-rose-950/40 hover:text-rose-400 transition-colors text-sm text-left font-medium"
                >
                    <span class="text-base w-5 text-center">🚪</span>
                    <span v-if="isSidebarExpanded">Secure Sign Out</span>
                </button>
                <div v-if="isSidebarExpanded" class="px-4 pb-4 text-center text-[10px] text-slate-600 font-mono">
                    Build Production v2.2.0
                </div>
            </div>
        </aside>

        <!-- 🖥 MAIN WORKSPACE MODULE -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto relative z-10">
            
            <!-- Main Navigation Header -->
            <header class="h-20 border-b border-slate-200 bg-white flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center space-x-4">
                    <button 
                        @click="toggleSidebar" 
                        class="p-2 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 transition-colors focus:outline-none"
                    >
                        <span class="text-base font-mono block w-5 h-5 flex items-center justify-center">☰</span>
                    </button>
                    <h1 class="text-xl font-bold text-slate-900 capitalize tracking-tight">{{ activePage.replace('-', ' ') }} Workspace</h1>
                </div>
                
                <!-- Telemetry Status Badges -->
                <div class="flex items-center space-x-3 bg-slate-100 px-4 py-2 rounded-xl border border-slate-200 text-xs">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-slate-600 font-medium">Online: <strong class="text-slate-900 font-bold">{{ onlineCount }}</strong></span>
                    </div>
                    <div class="w-px h-3 bg-slate-300"></div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span class="text-slate-600 font-medium">Offline: <strong class="text-slate-900 font-bold">{{ offlineCount }}</strong></span>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-6xl w-full mx-auto space-y-6">
                
                <!-- ==================== OVERVIEW HUB ==================== -->
                <div v-if="activePage === 'overview'" class="space-y-6">
                    <!-- Performance Metrics Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Registered Students</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ metrics?.total_students ?? 0 }}</h3>
                        </div>
                        <div class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm flex flex-col justify-between">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Teachers</p>
                            <h3 class="text-3xl font-extrabold text-slate-900 mt-2 tracking-tight">{{ metrics?.total_teachers ?? 0 }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col justify-between border transition-all duration-200" :class="(metrics?.pending_teachers ?? 0) > 0 ? 'border-amber-300 bg-gradient-to-br from-amber-50/40 to-white' : 'border-slate-200'">
                            <p class="text-xs font-bold uppercase tracking-wider" :class="(metrics?.pending_teachers ?? 0) > 0 ? 'text-amber-800' : 'text-slate-400'">Pending Approvals</p>
                            <h3 class="text-3xl font-extrabold mt-2 tracking-tight" :class="(metrics?.pending_teachers ?? 0) > 0 ? 'text-amber-700' : 'text-slate-900'">{{ metrics?.pending_teachers ?? 0 }}</h3>
                        </div>
                    </div>

                    <!-- Direct Shortcuts -->
                    <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                        <div>
                            <h4 class="font-bold text-white text-base tracking-tight">System Operational Controls</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Quickly jump across directory node paths for database state modifications.</p>
                        </div>
                        <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                            <button @click="activePage = 'users'" class="flex-1 sm:flex-initial px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                                Manage User Accounts
                            </button>
                            <button @click="activePage = 'teacher-onboarding'" class="flex-1 sm:flex-initial px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs rounded-lg transition-colors">
                                View Onboarding Verification
                            </button>
                        </div>
                    </div>

                    <!-- Analytics Graph Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 bg-white border border-slate-200 p-6 rounded-xl shadow-sm">
                            <h3 class="font-bold text-sm text-slate-900 mb-6">User Account Signups Growth Chart</h3>
                            <div class="h-40 flex items-end justify-between border-b border-l border-slate-200 px-4 pt-4">
                                <div v-for="item in monthlySignups" :key="item.month" class="flex flex-col items-center flex-1 mx-2 group">
                                    <div :style="{ height: item.percentage }" class="w-full bg-blue-600 rounded-t-sm hover:bg-blue-500 transition-all duration-150 relative">
                                        <span class="absolute -top-7 left-1/2 transform -translate-x-1/2 text-[10px] font-bold font-mono bg-slate-900 px-2 py-0.5 rounded text-white opacity-0 group-hover:opacity-100 transition-opacity shadow-md">{{ item.count }}</span>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-2 font-medium">{{ item.month }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-slate-200 p-5 rounded-xl shadow-sm space-y-4">
                            <h3 class="font-bold text-sm text-slate-900">System Target Capacity Metrics</h3>
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold"><span class="text-slate-500">Student Target Milestone</span><span class="text-slate-900 font-bold">75%</span></div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden"><div class="bg-blue-600 h-full rounded-full" style="width: 75%"></div></div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold"><span class="text-slate-500">Teacher Account Verification Audits</span><span class="text-emerald-600 font-bold">92%</span></div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden"><div class="bg-emerald-500 h-full rounded-full" style="width: 92%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== TEACHER ONBOARDING LINK PANEL ==================== -->
                <div v-if="activePage === 'teacher-onboarding'" class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-200 bg-slate-50">
                        <h3 class="font-bold text-base text-slate-900 tracking-tight">Pending Instructor Application Registries</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Audit requested profiles and dispatch secure account activation credential hooks.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="border-b border-slate-200 bg-slate-100 text-xs font-bold text-slate-600">
                                <tr>
                                    <th class="p-4">Applicant Instructor</th>
                                    <th class="p-4">Requested Status</th>
                                    <th class="p-4 text-right">Onboarding Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                                <tr v-if="users.filter(u => u.role === 'teacher' && u.status === 'pending').length === 0">
                                    <td colspan="3" class="p-8 text-center text-slate-400 italic text-xs bg-white">No pending teacher onboarding requests found in database.</td>
                                    <input type="text" hidden>
                                </tr>
                                <tr v-for="teacher in users.filter(u => u.role === 'teacher' && u.status === 'pending')" :key="teacher.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ teacher.name }}</div>
                                        <div class="text-xs text-slate-500 font-mono mt-0.5">{{ teacher.email }}</div>
                                    </td>
                                    <td class="p-4"><span class="text-amber-800 font-semibold text-xs bg-amber-100 px-2.5 py-1 rounded-full border border-amber-200">● Pending Audit</span></td>
                                    <td class="p-4 text-right">
                                        <button @click="sendInviteLink(teacher.email)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                                            ✉️ Send Activation Link
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ==================== USER REGISTRY MAIN VIEW ==================== -->
                <div v-if="activePage === 'users'" class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-4 border-b border-slate-200 bg-slate-50"><h3 class="font-bold text-base text-slate-900">User Accounts Registry</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-100 text-xs font-bold text-slate-600">
                                    <th class="p-4">User Details</th>
                                    <th class="p-4">Assigned Role</th>
                                    <th class="p-4">Current Status</th>
                                    <th class="p-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                                <tr v-if="users.length === 0"><td colspan="4" class="p-6 text-center text-slate-400 italic">No users available in database.</td></tr>
                                <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ user?.name }}</div>
                                        <div class="text-xs text-slate-500 font-mono mt-0.5">{{ user?.email }}</div>
                                    </td>
                                    <td class="p-4 capitalize text-slate-800 font-medium">{{ user?.role }}</td>
                                    <td class="p-4">
                                        <!-- Dynamic reactive text & badge updates -->
                                        <span :class="user?.status === 'approved' ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : user?.status === 'pending' ? 'text-amber-600 bg-amber-50 border-amber-200' : 'text-rose-600 bg-rose-50 border-rose-200'" class="text-xs font-bold capitalize px-2 py-1 rounded-full border">
                                            ● {{ user?.status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right space-x-1">
                                        <template v-if="user?.role !== 'admin'">
                                            <!-- Conditional state switching action triggers -->
                                            <button v-if="user?.status !== 'approved'" :disabled="userStatusForm.processing" @click="updateUserStatus(user.id, 'approved')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow disabled:opacity-50 transition-colors">Approve</button>
                                            <button v-if="user?.status !== 'suspended'" :disabled="userStatusForm.processing" @click="updateUserStatus(user.id, 'suspended')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-lg shadow disabled:opacity-50 transition-colors">Suspend</button>
                                        </template>
                                        <span v-else class="text-xs text-slate-500 font-mono bg-slate-100 px-2 py-1 rounded border border-slate-200">Root Management Core</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ==================== COURSE MODERATION SYSTEM ==================== -->
                <div v-if="activePage === 'courses'" class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="p-4 border-b border-slate-200 bg-slate-50"><h3 class="font-bold text-base text-slate-900">Course Catalog Approvals</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-100 text-xs font-bold text-slate-600">
                                    <th class="p-4">Course Title</th>
                                    <th class="p-4">Instructor Owner</th>
                                    <th class="p-4">Public Status</th>
                                    <th class="p-4 text-right">Moderation Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                                <tr v-if="courses.length === 0"><td colspan="4" class="p-6 text-center text-slate-400 italic">No courses discovered.</td></tr>
                                <tr v-for="course in courses" :key="course.id" class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ course?.title }}</div>
                                        <div class="text-xs text-slate-500 font-mono mt-0.5">{{ course?.category }}</div>
                                    </td>
                                    <td class="p-4 text-slate-700 font-medium">{{ course?.teacher }}</td>
                                    <td class="p-4 capitalize text-slate-600 font-mono text-xs">{{ course?.status }}</td>
                                    <td class="p-4 text-right space-x-1">
                                        <button v-if="course?.status !== 'published'" :disabled="courseStatusForm.processing" @click="updateCourseStatus(course.id, 'published')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow">Publish</button>
                                        <button v-if="course?.status !== 'rejected' && course?.status !== 'published'" :disabled="courseStatusForm.processing" @click="updateCourseStatus(course.id, 'rejected')" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-white text-xs font-semibold rounded-lg shadow">Reject</button>
                                        <button v-if="course?.status === 'published'" :disabled="courseStatusForm.processing" @click="updateCourseStatus(course.id, 'suspended')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-lg shadow">Suspend</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ==================== PROFILE SETTINGS PANEL ==================== -->
                <div v-if="activePage === 'profile'" class="bg-white border border-slate-200 p-6 rounded-xl shadow-sm max-w-2xl">
                    <h3 class="font-bold text-base text-slate-900 mb-1">Administrative Profile Settings</h3>
                    <p class="text-xs text-slate-500 mb-6">Modify platform administrator access credentials.</p>
                    
                    <form @submit.prevent="submitProfileUpdate" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Administrator Display Name</label>
                            <input v-model="profileForm.name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">Secure Gateway Email Endpoint</label>
                            <input v-model="profileForm.email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm text-slate-800 focus:outline-none focus:border-blue-500" required>
                        </div>
                        <div class="pt-2">
                            <button type="submit" :disabled="profileForm.processing" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</template>