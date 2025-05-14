// src/config/apiConfig.ts
// base url for the API
export const API_BASE_URL = 'http://127.0.0.1:8000';

// list of API endpoints
export const API_ENDPOINTS = {
    login: '/users/login',
    refreshToken: '/api/auth/token/refresh',
    register: '/users',
    getAllUsers: '/users',
    getUser: (id: number) => `/users/${id}`,
    updateUser: (id: number) => `/users/${id}`,
    deleteUser: (id: number) => `/users/${id}`,
    getAllExercises: '/exercises',
    createExercise: '/exercises',
    getExercise: (id: number) => `/exercises/${id}`,
    updateExercise: (id: number) => `/exercises/${id}`,
    deleteExercise: (id: number) => `/exercises/${id}`,
    getAllSets: '/sets',
    createSet: '/sets',
    getSet: (id: number) => `/sets/${id}`,
    updateSet: (id: number) => `/sets/${id}`,
    deleteSet: (id: number) => `/sets/${id}`,
    getAllTemplates: '/templates',
    createTemplate: '/templates',
    getTemplate: (id: number) => `/templates/${id}`,
    updateTemplate: (id: number) => `/templates/${id}`,
    deleteTemplate: (id: number) => `/templates/${id}`,
    getAllWorkouts: '/workouts',
    createWorkout: '/workouts',
    getWorkout: (id: number) => `/workouts/${id}`,
    updateWorkout: (id: number) => `/workouts/${id}`,
    deleteWorkout: (id: number) => `/workouts/${id}`,
    getAllMuscleGroups: '/muscle_groups',
    createMuscleGroup: '/muscle_groups',
    getMuscleGroup: (id: number) => `/muscle_groups/${id}`,
    updateMuscleGroup: (id: number) => `/muscle_groups/${id}`,
    deleteMuscleGroup: (id: number) => `/muscle_groups/${id}`,
    getAllExercisesByMuscleGroup: (id: number) => `/muscle_groups/${id}/exercises`,
};


//function to get the default headers for the API requests
export const getDefaultHeaders = (): HeadersInit => {
  const token = localStorage.getItem("token");
  return {
    "Content-Type": "application/json",
    "Authorization": token ? `Bearer ${token}` : "",
    "Accept-Language": "fr",
  };
};
