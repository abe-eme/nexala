<!-- Add this component layout into your Admin dashboard view to verify self-paced course assets -->
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    pendingCourses: {
        type: Array,
        default: () => [
            { id: 9, title: 'Advanced Scripting & Runtime Lifecycles', code: 'NEX-302', creator: 'Prof. Marcus Brody', status: 'pending_review' }
        ]
    }
});

const verifyCourse = (courseId, approveAction) => {
    router.post(route('admin.courses.verify', courseId), {
        approved: approveAction
    }, {
        onSuccess: () => {
            alert(approveAction ? 'Course cleared and published to Student Registry Catalog!' : 'Course sent back for revisions.');
        }
    });
};
</script>

<template>
    <div class="border rounded-2xl p-5 bg-white border-slate-200 shadow-xs space-y-4 mt-8 text-xs">
        <div class="border-b pb-2 border-slate-100">
            <h3 class="font-black uppercase tracking-wider text-[11px] text-slate-900">Curriculum Compliance & Publication Terminal</h3>
            <p class="text-slate-400 text-[10px]">Review self-paced courses uploaded by instructors before deployment to the student application layer.</p>
        </div>

        <div v-if="pendingCourses.length === 0" class="p-6 text-center font-mono text-slate-400 border border-dashed rounded-xl">
            Zero courses currently awaiting system verification clearance.
        </div>

        <div v-else class="space-y-2">
            <div 
                v-for="course in pendingCourses" 
                :key="course.id"
                class="flex flex-col md:flex-row md:items-center justify-between p-4 border border-slate-100 rounded-xl bg-slate-50/50 gap-4"
            >
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="bg-indigo-50 font-mono text-[9px] font-bold px-1.5 py-0.5 rounded text-indigo-600 border border-indigo-100">{{ course.code }}</span>
                        <span class="text-[10px] text-slate-400">Created by: {{ course.creator }}</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-[12px]">{{ course.title }}</h4>
                </div>

                <div class="flex gap-2">
                    <button 
                        @click="verifyCourse(course.id, false)"
                        type="button"
                        class="px-3 py-1.5 border border-rose-200 hover:bg-rose-50 text-rose-600 font-mono font-bold rounded-lg uppercase tracking-wider transition-all text-[9px] cursor-pointer"
                    >
                        Reject
                    </button>
                    <button 
                        @click="verifyCourse(course.id, true)"
                        type="button"
                        class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-black rounded-lg uppercase tracking-wider transition-all text-[9px] shadow-sm cursor-pointer"
                    >
                        Approve & Publish
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>