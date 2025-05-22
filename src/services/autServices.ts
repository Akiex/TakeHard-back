import { jwtDecode } from "jwt-decode";
import { API_BASE_URL, API_ENDPOINTS } from "../config/apiConfig";

export const getAccessToken = (): string | null =>
  localStorage.getItem("token");
export const getRefreshToken = (): string | null =>
  localStorage.getItem("refresh_token");
export const setAccessToken = (token: string) =>
  localStorage.setItem("token", token);
export const clearSession = () => {
  localStorage.clear();
  window.location.href = "/";
};
export const loginService = async (
  email: string,
  password: string
): Promise<{ token: string; isAdmin: boolean; userId: number }> => {
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
  // Ajuste selon ta réponse :
  const token: string = data.token || data.response.tokens.access;
  const refresh: string | undefined =
    data.refresh_token || data.response?.tokens?.refresh;

  setAccessToken(token);
  if (refresh) localStorage.setItem("refresh_token", refresh);

  // Décodage JWT pour extraire le rôle
  const decoded: any = jwtDecode(token);
  const isAdmin = decoded.role === "admin";
  const userId = decoded.user_id;
  return { token, isAdmin, userId };
};
