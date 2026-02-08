<script setup>
defineProps({
  users: {
    type: Array,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["edit", "delete", "retry"]);
</script>

<template>
  <div>
    <div v-if="loading" class="rounded-xl bg-slate-100 p-5 text-center text-slate-600">
      Loading users...
    </div>
    <div v-else-if="error" class="rounded-xl bg-slate-100 p-5 text-center text-slate-600">
      <p>{{ error }}</p>
      <button
        class="mt-3 rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5"
        type="button"
        @click="emit('retry')"
      >
        Try again
      </button>
    </div>
    <div v-else-if="!users.length" class="rounded-xl bg-slate-100 p-5 text-center text-slate-600">
      No users yet. Add the first one.
    </div>

    <div v-else class="flex flex-col gap-4">
      <div class="sticky top-0 z-10 hidden grid-cols-[1.4fr_1.2fr_1fr_1.2fr_1.2fr] gap-4 border-b border-slate-200 bg-white px-2 py-2 text-[0.7rem] uppercase tracking-[0.12em] text-slate-500 md:grid">
        <span>Name</span>
        <span>Email</span>
        <span>Contact</span>
        <span>Notes</span>
        <span>Actions</span>
      </div>
      <article v-for="user in users" :key="user.id" class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="grid grid-cols-1 items-center gap-4 md:grid-cols-[1.4fr_1.2fr_1fr_1.2fr_1.2fr]">
          <div class="flex flex-col gap-1">
            <h3 class="font-['Source_Serif_4'] text-lg">{{ user.first_name }} {{ user.last_name }}</h3>
            <p class="text-sm text-slate-500">{{ user.company_name }}</p>
          </div>
          <div class="flex flex-col gap-1 text-sm">
            <span class="inline-flex w-fit rounded-lg bg-slate-200 px-2 py-1 text-xs font-semibold text-slate-700">
              {{ user.email }}
            </span>
          </div>
          <div class="flex flex-col gap-1 text-sm">
            <strong class="text-slate-700">Contact</strong>
            <span>{{ user.contact_number }}</span>
          </div>
          <div v-if="user.description" class="text-sm text-slate-500">
            <span>{{ user.description }}</span>
          </div>
          <div v-else class="text-sm text-slate-400">-</div>
          <div class="flex flex-wrap justify-end gap-2">
            <button
              class="inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5"
              type="button"
              @click="emit('edit', user)"
            >
              <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                <path
                  d="M4 17.25V20h2.75L17.81 8.94l-2.75-2.75L4 17.25Z"
                  fill="currentColor"
                />
                <path
                  d="M19.71 6.04a1 1 0 0 0 0-1.41l-1.34-1.34a1 1 0 0 0-1.41 0l-1.06 1.06 2.75 2.75 1.06-1.06Z"
                  fill="currentColor"
                />
              </svg>
              Edit
            </button>
            <button
              class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-2 text-sm font-semibold text-red-700 transition hover:-translate-y-0.5"
              type="button"
              @click="emit('delete', user)"
            >
              <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                <path d="M6 7h12l-1 13H7L6 7Z" fill="currentColor" />
                <path d="M9 4h6l1 2H8l1-2Z" fill="currentColor" />
              </svg>
              Delete
            </button>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>
