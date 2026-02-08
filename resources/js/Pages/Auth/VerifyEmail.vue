<script setup>
import { computed } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
  status: {
    type: String,
  },
});

const form = useForm({});

const submit = () => {
  form.post(route("verification.send"));
};

const verificationLinkSent = computed(
  () => props.status === "verification-link-sent",
);
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />

    <div class="flex flex-col gap-6">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-500">
          Verify email
        </p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Check your inbox</h1>
        <p class="mt-2 text-sm text-slate-600">
          Before getting started, please click the verification link we emailed you.
        </p>
      </div>

      <div
        v-if="verificationLinkSent"
        class="rounded-lg bg-emerald-50 px-3 py-2 text-sm text-emerald-700"
      >
        A new verification link has been sent to the email address you provided.
      </div>

      <form class="flex flex-col gap-4" @submit.prevent="submit">
        <div class="flex flex-wrap items-center justify-between gap-3">
          <button
            class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-amber-100 shadow-[0_12px_20px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0"
            type="submit"
            :disabled="form.processing"
          >
            Resend verification email
          </button>

          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-sm font-semibold text-slate-600 underline decoration-slate-300 underline-offset-4 transition hover:text-slate-900"
          >
            Log out
          </Link>
        </div>
      </form>
    </div>
  </GuestLayout>
</template>
