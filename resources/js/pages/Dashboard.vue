<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type TaskItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { computed } from 'vue';
import { DateTime } from 'luxon';
import { CircleAlertIcon, CalendarClockIcon, ClockAlertIcon, CalendarIcon } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const tasks: TaskItem[] = [
    {
        title: "Buy groceries",
        description: "Pick up milk, eggs, and bread from the store.",
        priority: false,
        due_at: DateTime.fromISO("2025-03-01T04:30:00Z")
    },
    {
        title: "Finish project report",
        description: "Complete the final draft and submit it to the manager.",
        priority: false,
        due_at: DateTime.fromISO("2025-03-03T10:00:00Z")
    },
    {
        title: "Call the plumber",
        description: "Schedule a repair for the leaking sink in the kitchen.",
        priority: false,
        due_at: DateTime.fromISO("2025-03-02T14:30:00Z")
    },
    {
        title: "Workout session",
        description: "Attend the 1-hour strength training at the gym.",
        priority: false,
        due_at: DateTime.fromISO("2025-03-01T18:00:00Z")
    },
    {
        title: "Book flight tickets",
        description: "Reserve seats for the upcoming vacation trip.",
        priority: false,
        due_at: DateTime.fromISO("2025-02-28T12:00:00Z")
    },
    {
        title: "Read a book",
        description: "Finish reading the last three chapters of the novel.",
        priority: true,
        due_at: DateTime.fromISO("2025-02-02T20:00:00Z")
    }
];

const isDueToday = task => DateTime.now().hasSame(task.due_at, 'day');

const isPast = task => task.due_at < DateTime.now();

const groomedTasks = computed(() => tasks.map(task => ({
    ...task,
    ...{
        today: isDueToday(task),
        past: isPast(task),
        icon: task.priority ? CircleAlertIcon : isDueToday(task) ? ClockAlertIcon : CalendarClockIcon,
    }
})).sort(task => {
    if (task.past) return -1;
    if (task.today) return -1;

    return 1;
}));
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 flex justify-center rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-4">
                <div class="space-y-4 max-w-3xl">
                    <div v-for="(task, iDx) in groomedTasks" :key="iDx" class="rounded-xl shadow-lg border p-4 pl-0 cursor-pointer"
                    :class="{
                        [`bg-red-400/10 hover:bg-red-400/20 dark:bg-red-900/50 dark:hover:bg-red-900/30 border-red-400/70 dark:border-red-400`]: task.past,
                        [`bg-blue-400/10 hover:bg-blue-400/20 dark:bg-blue-900/50 dark:hover:bg-blue-900/30 border-blue-400/70 dark:border-blue-400`]: ! task.past && task.today,
                        [`bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-black/10 border-gray-400/70 dark:border-gray-400`]: ! task.past && ! task.today,
                    }">
                        <div class="flex items-center">
                            <div class="p-6">
                                <component :is="task.icon" />
                            </div>
                            <div class="items-center space-y-2">
                                <h1>{{ task.title }}</h1>
                                <p class="text-sm text-gray-400 font-medium">{{ task.description }}</p>
                                <div class="flex items-center space-x-2">
                                    <component :is="CalendarIcon" class="w-4 text-gray-600" />
                                    <span class="text-xs font-medium text-gray-600 mt-0.5">{{ task.due_at.toRelative() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
