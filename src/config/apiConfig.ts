// src/config/apiConfig.ts
// base url for the API
export const API_BASE_URL = import.meta.env.VITE_API_URL ?? '';

export const API_ENDPOINTS = {
  //USers
  login: "/users/login",
  refreshToken: "/api/auth/token/refresh",
  register: "/users",
  getAllUsers: "/users",
  getUser: (id: number) => `/users/${id}`,
  updateUser: (id: number) => `/users/${id}`,
  deleteUser: (id: number) => `/users/${id}`,
  //exercises
  getAllExercises: "/exercises",
  createExercise: "/exercises",
  getExercise: (id: number) => `/exercises/${id}`,
  updateExercise: (id: number) => `/exercises/${id}`,
  deleteExercise: (id: number) => `/exercises/${id}`,
  //sets
  getAllSets: "/sets",
  createSet: "/sets",
  getSet: (id: number) => `/sets/${id}`,
  updateSet: (id: number) => `/sets/${id}`,
  deleteSet: (id: number) => `/sets/${id}`,
  //templates
  getAllTemplates: "/templates",
  createTemplate: "/templates",
  getTemplate: (id: number) => `/templates/${id}`,

  getTemplateByUser: (id: number) => `/users/${id}/templates`,

  updateTemplate: (id: number) => `/templates/${id}`,
  deleteTemplate: (id: number) => `/templates/${id}`,
  //muscle groups
  getAllMuscleGroups: "/muscle-groups",
  createMuscleGroup: "/muscle-groups",
  getMuscleGroup: (id: number) => `/muscle-groups/${id}`,
  updateMuscleGroup: (id: number) => `/muscle-groups/${id}`,
  deleteMuscleGroup: (id: number) => `/muscle-groups/${id}`,
  getAllExercisesByMuscleGroup: (id: number) =>
    `/muscle_groups/${id}/exercises`,

  delete: (ressource: string, id: number) => `/${ressource}/${id}`,
  update: (ressource: string, id: number) => `/${ressource}/${id}`,
};

//function to get the default headers for the API requests
export const getDefaultHeaders = (): HeadersInit => {
  const token = localStorage.getItem("token");
  return {
    "Content-Type": "application/json",
    Authorization: token ? `Bearer ${token}` : "",
    "Accept-Language": "fr",
    
  };
};
