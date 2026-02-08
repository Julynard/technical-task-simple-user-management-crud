import { computed, onBeforeUnmount, ref, watch } from "vue";
import userService from "@/Services/userService";

export default function useUsers() {
  const users = ref([]);
  const meta = ref(null);
  const loading = ref(false);
  const loadError = ref("");
  const search = ref("");
  const currentPage = ref(1);
  const perPage = ref(10);

  const loadUsers = async (page = 1) => {
    loading.value = true;
    loadError.value = "";
    try {
      currentPage.value = page;
      const response = await userService.getUsers({
        page: currentPage.value,
        per_page: perPage.value,
        q: search.value || undefined,
      });
      const payload = response ?? {};
      const list = payload.data?.data ?? payload.data ?? [];
      users.value = list;
      meta.value = payload.meta ?? payload.data?.meta ?? null;
    } catch {
      loadError.value =
        "We couldn't load the users right now. Please try again in a moment.";
      users.value = [];
      meta.value = null;
    } finally {
      loading.value = false;
    }
  };

  const deleteUser = async (userId) => {
    await userService.deleteUser(userId);
  };

  const paginationLinks = computed(() => meta.value?.links ?? []);

  const formatPageLabel = (label) => {
    if (!label) return "";
    return label
      .replace(/&laquo;/g, "<<")
      .replace(/&raquo;/g, ">>")
      .replace(/<[^>]*>/g, "")
      .trim();
  };

  const goToPage = (url) => {
    if (!url) return;
    const match = url.match(/page=(\d+)/);
    if (!match) return;
    loadUsers(Number(match[1]));
  };

  const searchDebounceMs = 300;
  let searchTimerId = null;

  watch(search, () => {
    if (searchTimerId) {
      clearTimeout(searchTimerId);
    }
    searchTimerId = setTimeout(() => {
      loadUsers(1);
    }, searchDebounceMs);
  });

  onBeforeUnmount(() => {
    if (searchTimerId) {
      clearTimeout(searchTimerId);
    }
  });

  return {
    users,
    meta,
    loading,
    loadError,
    search,
    currentPage,
    perPage,
    paginationLinks,
    loadUsers,
    deleteUser,
    formatPageLabel,
    goToPage,
  };
}
