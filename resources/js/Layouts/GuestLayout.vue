<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- Master Dark Grid Screen Wrapper -->
    <div class="min-h-screen bg-[#070a14] flex flex-col justify-center items-center px-4 relative overflow-hidden">
        
        <!-- Grid Pattern Backdrop Overlay -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:3rem_3rem] opacity-20 pointer-events-none"></div>
        <div class="absolute w-[300px] h-[300px] bg-indigo-600/10 rounded-full blur-[90px] top-1/4 left-1/4 pointer-events-none"></div>

        <!-- Clean Custom Logo Branding -->
        <div class="mb-6 z-10">
            <Link href="/" class="inline-flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white text-sm">N</div>
                <span class="text-base font-black tracking-widest text-white">NEXALA</span>
            </Link>
        </div>

        <!-- High-End Dark Glass Panel Box -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-[#0f1424]/80 border border-gray-800 backdrop-blur-md shadow-xl rounded-2xl z-10">
            <slot />
        </div>
    </div>
</template>