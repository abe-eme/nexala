<!-- resources/js/Pages/Admin/Users/Index.vue -->
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

// Form props mapped out cleanly from Laravel Controller endpoints
const props = defineProps({
    users: {
        type: Array,
        default: () => []
    },
    metrics: {
        type: Object,
        default: () => ({})
    }
});

const userStatusForm = useForm({ status: '' });

// Trigger asynchronous inline state change preserving list scroll context
const updateUserStatus = (userId, newStatus) => {
    userStatusForm.status = newStatus;
    userStatusForm.patch(route('admin.users.status', userId), { preserveScroll: true });
};
</script>

<template>
    <!-- Explicit wrapper component layout assignment avoids named-slot compilation crashes -->
    <AdminLayout>
        
        <!-- Inject Title text directly upward into header slot -->
        <template #header>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">User Accounts Registry</h1>
        </template>

        <!-- Main Workspace Table View Block -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <div class="p-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-sm text-slate-700">Account Access Control Database</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-100 text-xs font-bold text-slate-600">
                            <th class="p-4">User Identity Details</th>
                            <th class="p-4">Assigned Access Role</th>
                            <th class="p-4">Operational Status</th>
                            <th class="p-4 text-right">Moderation Controls</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                        <tr v-if="users.length === 0">
                            <td colspan="4" class="p-8 text-center text-slate-400 italic text-xs bg-white">
                                No verified user profiles discovered within database records.
                            </td>
                        </tr>
                        <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-slate-900 text-sm">{{ user?.name }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">{{ user?.email }}</div>
                            </td>
                            <td class="p-4 capitalize text-slate-800 font-medium">{{ user?.role }}</td>
                            <td class="p-4">
                                <!-- Reactive Badge Indicators -->
                                <span 
                                    :class="user?.status === 'approved' ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : user?.status === 'pending' ? 'text-amber-600 bg-amber-50 border-amber-200' : 'text-rose-600 bg-rose-50 border-rose-200'" 
                                    class="text-xs font-bold capitalize px-2.5 py-1 rounded-full border"
                                >
                                    ● {{ user?.status }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-1">
                                <template v-if="user?.role !== 'admin'">
                                    <!-- Mutation State Event Fire buttons -->
                                    <button 
                                        v-if="user?.status !== 'approved'" 
                                        :disabled="userStatusForm.processing" 
                                        @click="updateUserStatus(user.id, 'approved')" 
                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg shadow disabled:opacity-50 transition-colors"
                                    >
                                        Approve
                                    </button>
                                    <button 
                                        v-if="user?.status !== 'suspended'" 
                                        :disabled="userStatusForm.processing" 
                                        @click="updateUserStatus(user.id, 'suspended')" 
                                        class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-lg shadow disabled:opacity-50 transition-colors"
                                    >
                                        Suspend
                                    </button>
                                </template>
                                <span v-else class="text-xs text-slate-400 font-mono bg-slate-50 px-2 py-1 rounded border border-slate-200">
                                    Root Protected Admin
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AdminLayout>
</template>