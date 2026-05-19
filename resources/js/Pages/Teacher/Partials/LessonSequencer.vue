<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    courseId: Number,
    lessons: {
        type: Array,
        default: () => [
            { id: 1, title: 'Introduction to Full-Stack Web Architectures', order: 1, gate_type: 'none', min_time: 0 },
            { id: 2, title: 'Vue State Lifecycle Managers', order: 2, gate_type: 'time_lock', min_time: 10 },
            { id: 3, title: 'Relational Database Engine Engineering', order: 3, gate_type: 'quiz_pass', min_time: 0 }
        ]
    }
});

const selectedLesson = ref(props.lessons[0]);

const form = useForm({
    gate_type: selectedLesson.value.gate_type,
    min_time: selectedLesson.value.min_time,
});

const selectLesson = (lesson) => {
    selectedLesson.value = lesson;
    form.gate_type = lesson.gate_type;
    form.min_time = lesson.min_time;
};

const saveLessonRules = () => {
    form.put(route('teacher.lessons.update-rules', selectedLesson.value.id), {
        onSuccess: () => {
            alert('Self-paced constraint rules successfully committed to backend database schema.');
        }
    });
};
</script>

<template>
    <div class="border rounded-2xl p-6 bg-white border-slate-200 shadow-xs space-y-6 text-xs text-slate-800">
        <div class="border-b pb-3 border-slate-100">
            <span class="bg-indigo-50 font-mono text-[9px] font-bold px-1.5 py-0.5 rounded text-indigo-600 border border-indigo-100">ENGINE CONFIG</span>
            <h3 class="text-sm font-black uppercase tracking-tight text-slate-900 mt-1">Linear Progression & Gating Controls</h3>
            <p class="text-slate-400 text-[10px] mt-0.5">Define exactly how students unlock content. These rules are strictly enforced at the database level on the student side.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Panel: The Lesson Order List Map -->
            <div class="md:col-span-1 border rounded-xl p-3 bg-slate-50/50 space-y-2">
                <p class="font-mono font-bold text-slate-400 text-[9px] uppercase tracking-wider px-1">Course Sequence Map</p>
                
                <div class="space-y-1">
                    <button
                        v-for="lesson in lessons"
                        :key="lesson.id"
                        @click="selectLesson(lesson)"
                        type="button"
                        :class="[selectedLesson.id === lesson.id ? 'bg-white border-indigo-600 shadow-xs font-bold text-indigo-600' : 'border-transparent hover:bg-white/60']"
                        class="w-full text-left p-2.5 border rounded-lg transition-all flex items-center gap-3 cursor-pointer text-[11px]"
                    >
                        <span class="font-mono text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">0{{ lesson.order }}</span>
                        <span class="truncate flex-1">{{ lesson.title }}</span>
                    </button>
                </div>
            </div>

            <!-- Right Panel: Rule Configuration Canvas -->
            <div class="md:col-span-2 border rounded-xl p-5 bg-white space-y-5">
                <div>
                    <h4 class="font-bold text-slate-900 text-[12px] truncate">Gating Configuration for: {{ selectedLesson.title }}</h4>
                    <p class="text-slate-400 text-[10px] mt-0.5 font-mono">ID Vector reference: lesson_node_{{ selectedLesson.id }}</p>
                </div>

                <form @submit.prevent="saveLessonRules" class="space-y-4">
                    <!-- Gate Type Radio Selectors -->
                    <div class="space-y-2">
                        <label class="block font-mono font-bold text-slate-400 uppercase tracking-wider text-[9px]">Unlock Requirement Condition</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Option 1: Instant -->
                            <label class="border rounded-xl p-3 flex flex-col justify-between gap-2 cursor-pointer transition-all hover:bg-slate-50" :class="[form.gate_type === 'none' ? 'border-indigo-600 bg-indigo-50/20' : 'border-slate-200']">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800">Instant</span>
                                    <input type="radio" v-model="form.gate_type" value="none" class="text-indigo-600 focus:ring-0" />
                                </div>
                                <p class="text-[10px] text-slate-400 leading-normal">Student can instantly click forward when ready.</p>
                            </label>

                            <!-- Option 2: Time Lock -->
                            <label class="border rounded-xl p-3 flex flex-col justify-between gap-2 cursor-pointer transition-all hover:bg-slate-50" :class="[form.gate_type === 'time_lock' ? 'border-indigo-600 bg-indigo-50/20' : 'border-slate-200']">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800">Time Gate</span>
                                    <input type="radio" v-model="form.gate_type" value="time_lock" class="text-indigo-600 focus:ring-0" />
                                </div>
                                <p class="text-[10px] text-slate-400 leading-normal">Requires student to spend a minimum time on the page.</p>
                            </label>

                            <!-- Option 3: Quiz Assessment Lock -->
                            <label class="border rounded-xl p-3 flex flex-col justify-between gap-2 cursor-pointer transition-all hover:bg-slate-50" :class="[form.gate_type === 'quiz_pass' ? 'border-indigo-600 bg-indigo-50/20' : 'border-slate-200']">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800">Evaluation Lock</span>
                                    <input type="radio" v-model="form.gate_type" value="quiz_pass" class="text-indigo-600 focus:ring-0" />
                                </div>
                                <p class="text-[10px] text-slate-400 leading-normal">Requires passing the associated module quiz.</p>
                            </label>
                        </div>
                    </div>

                    <!-- Dynamic Input Parameter Layer -->
                    <div v-if="form.gate_type === 'time_lock'" class="space-y-1.5 p-4 bg-amber-50/40 border border-amber-200/60 rounded-xl transition-all">
                        <label class="block font-mono font-bold text-amber-800 uppercase tracking-wider text-[9px]">Required Reading Duration (Minutes)</label>
                        <input 
                            v-model="form.min_time" 
                            type="number" 
                            min="1" 
                            class="w-full sm:w-32 p-2 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/20 bg-white font-mono font-bold text-amber-900" 
                        />
                        <p class="text-[10px] text-amber-700/80">The system layout countdown timer will enforce this before enabling the completion checkmark.</p>
                    </div>

                    <div v-if="form.gate_type === 'quiz_pass'" class="p-4 bg-indigo-50/40 border border-indigo-200/60 rounded-xl font-mono text-[10px] text-indigo-800 transition-all">
                        ⚡ system mapping note: This lesson node will bind automatically to the active assessment matrix linked to sequence index order.
                    </div>

                    <!-- Action Triggers -->
                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-slate-900 hover:bg-slate-800 text-white font-mono font-black px-4 py-2 rounded-xl text-[9px] uppercase tracking-wider transition-all cursor-pointer shadow-xs disabled:opacity-50"
                        >
                            Commit Gating Rules
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>