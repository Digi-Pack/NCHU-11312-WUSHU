<script setup lang="ts">
import Heading from '@/components/ProductCard.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
  {
    title: 'Profile',
    href: '/settings/profile',
  },
  {
    title: 'Password',
    href: '/settings/password',
  },
  {
    title: 'Appearance',
    href: '/settings/appearance',
  },
];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
  <div class="px-4 py-6">
    <Heading title="Settings" description="Manage your profile and account settings" />

    <div class="md:space-y-0 lg:flex-row lg:space-x-12 lg:space-y-0 flex flex-col space-y-8">
      <aside class="lg:w-48 w-full max-w-xl">
        <nav class="flex flex-col space-x-0 space-y-1">
          <Button
            v-for="item in sidebarNavItems"
            :key="item.href"
            variant="ghost"
            :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
            as-child
          >
            <Link :href="item.href">
              {{ item.title }}
            </Link>
          </Button>
        </nav>
      </aside>

      <Separator class="md:hidden my-6" />

      <div class="md:max-w-2xl flex-1">
        <section class="max-w-xl space-y-12">
          <slot />
        </section>
      </div>
    </div>
  </div>
</template>
