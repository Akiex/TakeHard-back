// src/hooks/useApiResource.ts
import { useEffect, useState } from "react";
import { apiFetch } from "../services/apiClient";

/**
 * Generic hook to fetch data from an API endpoint.
 */
export function useApiResource<T>(
  endpoint: string | null,
  options?: RequestInit
) {
  const [data, setData] = useState<T | null>(null);
  const [loading, setLoading] = useState<boolean>(!!endpoint);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!endpoint) return;

    const fetchData = async () => {
      setLoading(true);
      setError(null);
      try {
        const result = await apiFetch(endpoint, options);
        setData(result);
      } catch (err: any) {
        setError(err.message || "Unexpected error");
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [endpoint]);

  return { data, loading, error };
}