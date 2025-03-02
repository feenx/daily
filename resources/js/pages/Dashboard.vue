<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type TaskItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { computed } from 'vue';
import { DateTime, Duration } from 'luxon';
import { CircleAlertIcon, CalendarClockIcon, ClockAlertIcon, CalendarIcon, SquareCheckIcon, SquareIcon, CalendarSyncIcon } from 'lucide-vue-next';

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
        priority: true,
        frequency: 'weekly',
        due_at: DateTime.now().minus(Duration.fromObject({ days: 3 })),
    },
    {
        title: "Finish project report",
        description: "Complete the final draft and submit it to the manager.",
        priority: false,
        frequency: 'monthly',
        due_at: DateTime.now().minus(Duration.fromObject({ hours: 3 })),
    },
    {
        title: "Call the plumber",
        description: "Schedule a repair for the leaking sink in the kitchen.",
        priority: false,
        due_at: DateTime.now().plus(Duration.fromObject({ hours: 3 })),
    },
    {
        title: "Workout session",
        description: "Attend the 1-hour strength training at the gym. Attend the 1-hour strength training at the gym. Attend the 1-hour strength training at the gym. Attend the 1-hour strength training at the gym.",
        priority: false,
        frequency: 'bi-weekly',
        due_at: DateTime.now().plus(Duration.fromObject({ days: 3 })),
    },
    {
        title: "Book flight tickets",
        description: "Reserve seats for the upcoming vacation trip.",
        priority: false,
        due_at: DateTime.now().plus(Duration.fromObject({ days: 6 })),
    },
    {
        title: "Read a book",
        description: "Finish reading the last three chapters of the novel.",
        priority: true,
        frequency: 'monthly',
        due_at: DateTime.now().plus(Duration.fromObject({ days: 12 })),
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
})).sort((taskA, taskB) => taskB.due_at < taskA.due_at));
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 flex justify-center rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-4">
                <div class="space-y-4 max-w-xl">
                    <div v-for="(task, iDx) in groomedTasks" :key="iDx" class="rounded-xl shadow-lg border"
                    :class="{
                        [`bg-red-400/10 hover:bg-red-400/20 dark:bg-red-900/50 dark:hover:bg-red-900/30 border-red-400/70 dark:border-red-400`]: task.past,
                        [`bg-blue-400/10 hover:bg-blue-400/20 dark:bg-blue-900/50 dark:hover:bg-blue-900/30 border-blue-400/70 dark:border-blue-400`]: ! task.past && task.today,
                        [`bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-black/10 border-gray-400/70 dark:border-gray-400`]: ! task.past && ! task.today,
                    }">
                        <div class="relative flex items-center py-4">
                            <div class="px-10">
                                <component :is="task.icon" />
                            </div>
                            <div class="flex-auto items-center space-y-2">
                                <h1 class="capitalize text-white text-lg">{{ task.title }}</h1>
                                <p class="text-sm text-gray-300 font-medium text-justify">{{ task.description }}</p>
                                <div class="flex space-x-2">
                                    <div v-if="task.frequency" class="flex items-center space-x-2 text-nowrap">
                                        <component :is="CalendarSyncIcon" class="w-4 text-gray-400" />
                                        <span class="text-xs font-medium text-gray-400 mt-0.5 capitalize">{{ task.frequency }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-nowrap">
                                        <component :is="CalendarIcon" class="w-4 text-gray-400" />
                                        <span class="text-xs font-medium text-gray-400 mt-0.5 capitalize">{{ task.due_at.toRelative() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="px-10 self-stretch flex flex-col justify-center items-center">
                                <component :is="SquareIcon" class="h-8 w-8 cursor-pointer hover:text-emerald-600" />
                            </div>
                            <div v-if="task.past || task.today" class="absolute bottom-2 right-3 flex items-center space-x-2 self-bottom">
                                <span v-if="task.priority" class="text-xs font-medium text-gray-400 mt-0.5">Priority</span>
                                <span v-else class="text-xs font-medium text-gray-400 mt-0.5">Auto Skipped</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
