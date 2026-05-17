<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { LogIn, UserPlus, HelpCircle, Shield, Compass, BookOpen, Info, ArrowRight, CheckCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const activeFaq = ref(null);
const toggleFaq = (index) => {
    activeFaq.value = activeFaq.value === index ? null : index;
};

const faqs = [
    {
        question: "What exactly is Nexala?",
        answer: "Nexala is an easy-to-use digital school. Instructors use our platform to teach lessons through clear videos, and you can learn digital skills at your own comfortable pace."
    },
    {
        question: "Do I need to pay anything to create an account?",
        answer: "No! Creating an account is completely free. Just click the 'Create Account' button to begin your learning journey."
    },
    {
        question: "What if I get stuck or forget my password?",
        answer: "Don't panic! We have simple recovery steps on the login page, and our help section is always available to guide you back into your account safely."
    }
];
</script>

<template>
    <Head title="Nexala - Future of Learning" />

    <!-- Main Container with Integrated Tech Grid Background -->
    <div class="min-h-screen bg-[#0a0d1a] text-gray-200 font-sans relative overflow-hidden pb-16">
        
        <!-- Cyber Background Grid Layer -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-25 pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-indigo-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- Header Navbar -->
        <header class="sticky top-0 z-50 backdrop-blur-md bg-[#0a0d1a]/80 border-b border-gray-800/80">
            <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white text-xl shadow-md shadow-indigo-600/20">N</div>
                    <span class="text-xl font-black tracking-widest text-white">NEXALA</span>
                </div>

                <nav class="hidden md:flex items-center space-x-10 text-sm font-bold tracking-wider uppercase text-gray-300">
                    <a href="#home" class="hover:text-white transition-colors">Home</a>
                    <a href="#about" class="hover:text-white transition-colors">About Us</a>
                    <a href="#features" class="hover:text-white transition-colors">Features</a>
                    <a href="#faq" class="hover:text-white transition-colors">Help</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <Link :href="route('login')" class="flex items-center space-x-2 px-4 py-2.5 text-sm font-bold text-gray-300 hover:text-white">
                        <LogIn class="w-4 h-4 text-cyan-400" />
                        <span>Log In</span>
                    </Link>
                    <Link :href="route('register')" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-600/20 transition-all">
                        Create Account
                    </Link>
                </div>
            </div>
        </header>

        <!-- Hero Area -->
        <section id="home" class="max-w-5xl mx-auto px-6 pt-24 pb-16 text-center relative z-10">
            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold bg-indigo-500/10 text-cyan-400 border border-indigo-500/20 mb-8 uppercase tracking-wider">
                ✨ Simple Virtual Training Arena
            </span>
            <h1 class="text-4xl md:text-6xl font-black tracking-tight text-white max-w-3xl mx-auto leading-tight mb-6">
                Master Technical Skills <br />
                <span class="text-indigo-400">Without the Frustration</span>
            </h1>
            <p class="text-base md:text-lg text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                A clean learning dashboard designed to guide you through coding courses step-by-step with zero confusing setup.
            </p>
            <div class="flex justify-center gap-4">
                <Link :href="route('register')" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm uppercase tracking-wider transition-all shadow-lg shadow-indigo-600/20">
                    Start Learning Free
                </Link>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about" class="max-w-5xl mx-auto px-6 py-16 border-t border-gray-800/60 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="flex items-center space-x-2 text-cyan-400 font-bold text-xs uppercase tracking-widest mb-3">
                        <Info class="w-4 h-4" />
                        <span>Our Mission</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-6">We make software education easy for everyone.</h2>
                    <p class="text-gray-300 text-base leading-relaxed mb-6">
                        Nexala was built because regular online classes throw too much hard language at you. We believe education should feel like a helpful friend guiding you forward.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-sm text-gray-200">
                            <CheckCircle class="w-5 h-5 text-indigo-500 shrink-0" />
                            <span>No confusing terminology</span>
                        </div>
                        <div class="flex items-center space-x-3 text-sm text-gray-200">
                            <CheckCircle class="w-5 h-5 text-indigo-500 shrink-0" />
                            <span>Clean, bite-sized video modules</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900/50 p-8 rounded-2xl border border-gray-800 text-base text-gray-300 leading-relaxed shadow-inner">
                    <h3 class="font-bold text-white mb-3 text-lg">Our Simple Promise</h3>
                    We will never leave you stuck. If code breaks or you don't understand a system process, our platform is optimized to explain the solution simply so you can keep growing your skills.
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="max-w-5xl mx-auto px-6 py-16 border-t border-gray-800/60 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl">
                    <BookOpen class="w-6 h-6 text-indigo-400 mb-4" />
                    <h3 class="text-base font-bold text-white mb-2">Clear Lessons</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Watch high-quality lessons that track your status automatically.</p>
                </div>
                <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl">
                    <Compass class="w-6 h-6 text-cyan-400 mb-4" />
                    <h3 class="text-base font-bold text-white mb-2">Easy Navigation</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">A minimal control board built for absolute beginners.</p>
                </div>
                <div class="bg-gray-900/30 border border-gray-800 p-6 rounded-2xl">
                    <Shield class="w-6 h-6 text-purple-400 mb-4" />
                    <h3 class="text-base font-bold text-white mb-2">Verified Instructors</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Safe environment with trusted teachers checked by the system.</p>
                </div>
            </div>
        </section>

        <!-- Help Hub FAQ -->
        <section id="faq" class="max-w-3xl mx-auto px-6 py-16 border-t border-gray-800/60 relative z-10">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div v-for="(faq, index) in faqs" :key="index" class="bg-gray-900/20 border border-gray-800 rounded-xl overflow-hidden">
                    <button @click="toggleFaq(index)" class="w-full px-6 py-4 text-left flex justify-between items-center text-base font-bold text-white hover:bg-gray-800/30">
                        <span>{{ faq.question }}</span>
                        <span class="text-indigo-400 text-lg">{{ activeFaq === index ? '−' : '+' }}</span>
                    </button>
                    <div v-show="activeFaq === index" class="px-6 pb-4 text-sm text-gray-400 border-t border-gray-800/40 pt-3 leading-relaxed">
                        {{ faq.answer }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>