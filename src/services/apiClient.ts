// src/services/apiClient.ts

import { API_BASE_URL, getDefaultHeaders } from "../config/apiConfig";


interface RequestOptions extends RequestInit {
  useBaseUrl?: boolean;
}

export const apiFetch = async (endpoint: string, options: RequestOptions = {}) => {
  const {
    useBaseUrl = true,
    headers,
    ...rest
  } = options;

  const url = useBaseUrl ? `${API_BASE_URL}${endpoint}` : endpoint;

  const finalHeaders = {
    ...getDefaultHeaders(),
    ...headers,
  };

  const response = await fetch(url, {
    ...rest,
    headers: finalHeaders,
  });

  if (!response.ok) {
    const errorText = await response.text();
    throw new Error(`HTTP ${response.status} - ${errorText}`);
  }

  return response.json();
};