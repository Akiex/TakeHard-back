// src/services/authService.ts
import { API_BASE_URL, API_ENDPOINTS } from "../config/apiConfig";
export const getAccessToken = () => localStorage.getItem("token");
export const getRefreshToken = () => localStorage.getItem("refresh_token");
export const setAccessToken = (token: string) => localStorage.setItem("token", token);
export const clearSession = () => {
  localStorage.clear();
  window.location.href = "/";
};


/**
 * Request for login
 */
export const login = async (email: string, password: string): Promise<void> => {
    const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.login}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept-Language": "fr-FR",
      },
      body: JSON.stringify({ email, password }),
    });

    if (!response.ok) {
      throw new Error("Identifiants incorrects");
    }

    const data = await response.json();
    const tokens = data.response.tokens;

    localStorage.setItem("token", tokens.access);
    localStorage.setItem("refresh_token", tokens.refresh);
};

/**
 * Request for refreshing the access token
 */
export const refreshToken = async (): Promise<string | undefined> => {
  const refresh = getRefreshToken();
  if (!refresh) return;

  try {
    const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.refreshToken}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept-Language": "fr-FR",
      },
      body: JSON.stringify({ refresh }),
    });

    if (!response.ok) throw new Error("Échec du rafraîchissement du token");

    const data = await response.json();
    const newToken = data?.response?.token?.access;
    if (newToken) {
      setAccessToken(newToken);
      return newToken;
    }
  } catch (error) {
    console.error("Erreur de rafraîchissement du token :", error);
    clearSession();
  }
};

/**
 * Request wrapper for authenticated requests
 */
export const fetchWithAuth = async (url: string, options: RequestInit = {}) => {
  let token = getAccessToken();

  const defaultHeaders = {
    "Content-Type": "application/json",
    "Accept-Language": "fr-FR",
  };

  let response = await fetch(url, {
    ...options,
    headers: {
      ...defaultHeaders,
      ...options.headers,
      Authorization: `Bearer ${token}`,
    },
  });

  if (response.status === 401) {
    let token: string | null = (await refreshToken()) ?? null;
    if (!token) return response;

    response = await fetch(url, {
      ...options,
      headers: {
        ...defaultHeaders,
        ...options.headers,
        Authorization: `Bearer ${token}`,
      },
    });
  }

  return response;
};