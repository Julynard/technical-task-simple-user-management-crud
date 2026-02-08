<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
  canLogin: {
    type: Boolean,
    default: false,
  },
  canRegister: {
    type: Boolean,
    default: false,
  },
  users: {
    type: Array,
    default: () => [],
  },
});

const copiedId = ref(null);
let copiedTimer = null;

const totalUsers = computed(() => props.users.length);

const getInitials = (user) => {
  const first = user?.first_name?.trim() ?? "";
  const last = user?.last_name?.trim() ?? "";
  const initials = `${first[0] ?? ""}${last[0] ?? ""}`.toUpperCase();
  return initials || "U";
};

const getFullName = (user) => {
  const parts = [user?.first_name, user?.last_name].filter(Boolean);
  return parts.length ? parts.join(" ") : "Unnamed User";
};

const copyPhone = async (user) => {
  if (!user?.contact_number) return;
  const value = user.contact_number;
  try {
    await navigator.clipboard.writeText(value);
  } catch {
    const textarea = document.createElement("textarea");
    textarea.value = value;
    textarea.setAttribute("readonly", "");
    textarea.style.position = "absolute";
    textarea.style.left = "-9999px";
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
  }

  copiedId.value = user.id;
  if (copiedTimer) {
    clearTimeout(copiedTimer);
  }
  copiedTimer = setTimeout(() => {
    copiedId.value = null;
  }, 1800);
};
</script>

<template>
  <Head title="User Management System" />

  <div
    class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_#fff7ed,_#f8fafc_45%,_#e2e8f0)] text-slate-900"
  >
    <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full bg-amber-200/60 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 top-40 h-80 w-80 rounded-full bg-sky-200/60 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-rose-200/50 blur-[90px]"></div>

    <div class="relative mx-auto max-w-6xl px-6 py-16">
      <header class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-orange-500">
            User Management System
          </p>
          <nav v-if="canLogin" class="flex flex-wrap items-center gap-2">
            <Link
              v-if="$page.props.auth?.user"
              :href="route('dashboard')"
              class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-amber-100 transition hover:-translate-y-0.5"
            >
              Dashboard
            </Link>
            <template v-else>
              <Link
                :href="route('login')"
                class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-400"
              >
                Log in
              </Link>
              <Link
                v-if="canRegister"
                :href="route('register')"
                class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-amber-100 transition hover:-translate-y-0.5"
              >
                Register
              </Link>
            </template>
          </nav>
        </div>
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
              User Directory
            </h1>
            <p class="mt-3 max-w-2xl text-base text-slate-600">
              Browse every user record with instant access to contact details, company info, and notes.
            </p>
          </div>
          <div class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100">
            {{ totalUsers }} profiles
          </div>
        </div>
      </header>

      <section class="mt-10">
        <div
          v-if="!users.length"
          class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-10 text-center text-slate-600"
        >
          No users found yet. Seed the database to see sample profiles.
        </div>

        <div v-else class="grid gap-6 sm:grid-cols-2">
          <article
            v-for="user in users"
            :key="user.id"
            class="group relative rounded-2xl border border-slate-200/80 bg-white/80 p-6 shadow-[0_14px_32px_rgba(15,23,42,0.08)] backdrop-blur"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-center gap-4">
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-amber-100"
                >
                  {{ getInitials(user) }}
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-slate-900 truncate">
                    {{ getFullName(user) }}
                  </h2>
                  <p class="text-sm text-slate-500">
                    {{ user.company_name || "Company not listed" }}
                  </p>
                </div>
              </div>
            </div>

            <div class="mt-4 space-y-3 text-sm text-slate-600">
              <div class="flex items-center gap-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                  @
                </span>
                <span class="truncate">{{ user.email }}</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                  #
                </span>
                <span class="flex-1">{{ user.contact_number || "No contact number" }}</span>
                <button
                  class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60"
                  type="button"
                  :disabled="!user.contact_number"
                  @click="copyPhone(user)"
                >
                  <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                    <rect x="9" y="9" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                    <rect x="5" y="5" width="10" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                  </svg>
                  {{ copiedId === user.id ? "Copied" : "Copy" }}
                </button>
              </div>
              <p class="text-sm text-slate-500">
                {{ user.description || "No description provided." }}
              </p>
            </div>

            <div class="mt-6 flex justify-end">
              <a
                class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-amber-100 transition hover:-translate-y-0.5"
                :href="`mailto:${user.email}`"
              >
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                  <path
                    d="M4 6h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                  />
                  <path
                    d="m22 8-10 6L2 8"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                  />
                </svg>
                Send an Email
              </a>
            </div>
          </article>
        </div>
      </section>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&family=fraunces:500,600,700&display=swap");

div,
section,
header,
h1,
h2,
article {
  font-family: "Space Grotesk", "Segoe UI", system-ui, sans-serif;
}

h1,
h2 {
  font-family: "Fraunces", "Space Grotesk", serif;
}
</style>
