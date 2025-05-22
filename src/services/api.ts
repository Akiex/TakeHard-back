// services/api.ts
import {
  API_BASE_URL,
  API_ENDPOINTS,
  getDefaultHeaders,
} from "../config/apiConfig";

export const createUser = async (userData: {
  first_name: string;
  last_name: string;
  email: string;
  password: string;
  role: string;
}) => {
  const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.register}`, {
    method: "POST",
    headers: getDefaultHeaders(),
    body: JSON.stringify(userData),
  });

  if (!response.ok) {
    const errorData = await response.json();
    throw new Error(errorData.message || "Erreur lors de l'inscription");
  }

  return response.json();
};
