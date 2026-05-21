<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 flex flex-col font-sans">
    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-6 h-16 flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 bg-indigo-600 rounded-xl flex items-center justify-center font-black text-white tracking-wider shadow-lg shadow-indigo-600/20">
          N
        </div>
        <span class="font-bold text-lg tracking-tight text-slate-900">
          nexala<span class="text-indigo-600 font-medium">learn</span>
        </span>
        <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-600 rounded-full ml-2">
          Student Terminal
        </span>
      </div>

      <!-- Right Action Toolbar -->
      <div class="flex items-center gap-4">
        <button class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition relative">
          <span class="absolute top-2 right-2 w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
        </button>
        
        <div class="h-8 w-px bg-slate-200"></div>
        
        <div class="flex items-center gap-3">
          <div class="text-right hidden sm:block">
            <div class="text-sm font-semibold text-slate-800">{{ $page.props.auth.user.name }}</div>
            <div class="text-xs text-slate-500 capitalize">{{ $page.props.auth.user.role || 'Student' }}</div>
          </div>
          <div class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-indigo-600 shadow-inner">
            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
          </div>
        </div>
      </div>
    </header>

    <div class="flex flex-1">
      <!-- Left Sidebar Dynamic Navigation -->
      <aside class="w-64 border-r border-slate-200 bg-white p-4 hidden md:flex flex-col justify-between sticky top-16 h-[calc(100vh-4rem)]">
        <div class="space-y-1">
          <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-3 mb-3">Academic Core</div>
          
          <!-- Dashboard Control Link -->
          <Link 
            :href="route('student.dashboard')" 
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group border',
              route().current('student.dashboard') 
                ? 'bg-indigo-50 text-indigo-600 border-indigo-100 font-semibold' 
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-transparent'
            ]"
          >
            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
            </svg>
            Dashboard
          </Link>

          <!-- Courses Index Link -->
          <Link 
            :href="route('student.courses.index')" 
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group border',
              route().current('student.courses.*') 
                ? 'bg-indigo-50 text-indigo-600 border-indigo-100 font-semibold' 
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-transparent'
            ]"
          >
            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.246.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Course Catalog
          </Link>

          <!-- Assignments Index Link -->
          <Link 
            :href="route('student.assignments.index')" 
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group border',
              route().current('student.assignments.*') 
                ? 'bg-indigo-50 text-indigo-600 border-indigo-100 font-semibold' 
                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-transparent'
            ]"
          >
            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Task & Assignments
          </Link>
        </div>

        <!-- Logout Action Utility -->
        <div class="border-t border-slate-200 pt-4">
          <Link 
            :href="route('logout')" 
            method="post" 
            as="button" 
            class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl text-sm font-medium transition-all text-left"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1" />
            </svg>
            Sign Out Account
          </Link>
        </div>
      </aside>

      <!-- Central Content Stream Container -->
      <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        <!-- Optional Page Header Slot -->
        <div v-if="$slots.header" class="mb-6">
          <slot name="header" />
        </div>
        
        <!-- Main Component Body Slot -->
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>