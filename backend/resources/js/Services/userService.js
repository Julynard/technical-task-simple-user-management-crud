import axios from "axios";

const api = axios.create({
  baseURL: "",
  headers: {
    Accept: "application/json",
  },
});

const handleError = (error) => {
  if (error.response?.status === 422) {
    return Promise.reject({
      message: error.response.data?.message,
      errors: error.response.data?.errors ?? {},
    });
  }

  return Promise.reject(error);
};

const unwrap = (response) => {
  return response?.data ?? response;
};

export default {
  async getUsers(params = {}) {
    try {
      const response = await api.get("/api/users", { params });
      return unwrap(response);
    } catch (error) {
      return handleError(error);
    }
  },
  async createUser(payload) {
    try {
      const response = await api.post("/api/users", payload);
      return unwrap(response);
    } catch (error) {
      return handleError(error);
    }
  },
  async updateUser(id, payload) {
    try {
      const response = await api.put(`/api/users/${id}`, payload);
      return unwrap(response);
    } catch (error) {
      return handleError(error);
    }
  },
  async deleteUser(id) {
    try {
      const response = await api.delete(`/api/users/${id}`);
      return unwrap(response);
    } catch (error) {
      return handleError(error);
    }
  },
};
