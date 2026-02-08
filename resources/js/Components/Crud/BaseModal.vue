<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from "vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: "",
  },
  size: {
    type: String,
    default: "md",
  },
});

const emit = defineEmits(["close"]);

const modalRef = ref(null);
const previouslyFocused = ref(null);
const sizeClass = computed(() => {
  switch (props.size) {
    case "sm":
      return "max-w-md";
    case "lg":
      return "max-w-3xl";
    default:
      return "max-w-xl";
  }
});

const focusableSelector =
  'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

const trapFocus = (event) => {
  if (event.key !== "Tab" || !modalRef.value) return;
  const focusable = Array.from(
    modalRef.value.querySelectorAll(focusableSelector),
  ).filter((el) => !el.hasAttribute("disabled"));
  if (!focusable.length) return;

  const first = focusable[0];
  const last = focusable[focusable.length - 1];

  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault();
    last.focus();
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault();
    first.focus();
  }
};

const handleKeydown = (event) => {
  if (event.key === "Escape") {
    emit("close");
    return;
  }
  trapFocus(event);
};

const focusFirstElement = async () => {
  await nextTick();
  if (!modalRef.value) return;
  const focusable = modalRef.value.querySelectorAll(focusableSelector);
  if (focusable.length) {
    focusable[0].focus();
  }
};

watch(
  () => props.open,
  (isOpen) => {
    if (isOpen) {
      previouslyFocused.value = document.activeElement;
      focusFirstElement();
      document.addEventListener("keydown", handleKeydown);
    } else {
      document.removeEventListener("keydown", handleKeydown);
      previouslyFocused.value?.focus?.();
    }
  },
);

onBeforeUnmount(() => {
  document.removeEventListener("keydown", handleKeydown);
});
</script>

<template>
  <div
    v-if="open"
    class="fixed inset-0 z-50 grid place-items-center bg-slate-900/50 p-6"
    @click.self="emit('close')"
  >
    <div
      ref="modalRef"
      class="w-full rounded-[18px] border border-slate-200 bg-white shadow-[0_20px_40px_rgba(15,23,42,0.2)]"
      :class="sizeClass"
    >
      <header class="flex items-center justify-between px-6 pb-2 pt-5">
        <h3 class="font-['Source_Serif_4'] text-xl">{{ title }}</h3>
        <button
          class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-slate-900 transition hover:-translate-y-0.5"
          type="button"
          aria-label="Close modal"
          @click="emit('close')"
        >
          <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
            <path
              d="M6 6l12 12M18 6l-12 12"
              stroke="currentColor"
              stroke-width="2"
              fill="none"
            />
          </svg>
        </button>
      </header>
      <div class="px-6 pb-6">
        <slot />
      </div>
    </div>
  </div>
</template>
