<template>
  <div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">My Academic Performance</h1>
    
    <!-- Assignments Table -->
    <div class="bg-white shadow rounded p-4 mb-8">
      <h2 class="font-bold text-lg mb-4">Assignments</h2>
      <table class="w-full text-left">
        <tr v-for="a in assignments" :key="a.title" class="border-b">
          <td class="py-2">{{ a.title }}</td>
          <td class="py-2 font-bold">{{ a.score ?? 'Pending' }}</td>
          <td class="py-2 italic text-gray-500">{{ a.feedback }}</td>
        </tr>
      </table>
    </div>

    <!-- Quizzes Table -->
    <div class="bg-white shadow rounded p-4">
      <h2 class="font-bold text-lg mb-4">Quiz Scores</h2>
      <table class="w-full text-left">
        <tr v-for="q in quizzes" :key="q.title" class="border-b">
          <td class="py-2">{{ q.title }}</td>
          <td class="py-2 font-bold text-green-600">{{ q.score }}%</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script setup>
defineProps({ assignments: Array, quizzes: Array });
</script>