import {
  API_BASE_URL,
  API_ENDPOINTS,
  getDefaultHeaders,
} from "./../config/apiConfig";

// DELETE override
export async function apiDelete(resource: string, id: number) {
  const url = `${API_BASE_URL}${API_ENDPOINTS.delete(resource, id)}`;
  const res = await fetch(url, {
    method: "POST",                          // POST au lieu de DELETE
    headers: {
      ...getDefaultHeaders(),
      "X-HTTP-Method-Override": "DELETE",   // override
    },
  });
  if (!res.ok) throw new Error(`Erreur DELETE ${res.status}`);
  return res.json();
}

// UPDATE override
export async function apiUpdate(resource: string, id: number, payload: any) {
  const url = `${API_BASE_URL}${API_ENDPOINTS.update(resource, id)}`;
  const res = await fetch(url, {
    method: "POST",                          // POST au lieu de PUT
    headers: {
      ...getDefaultHeaders(),
      "Content-Type": "application/json",
      "X-HTTP-Method-Override": "PUT",      // override
    },
    body: JSON.stringify(payload),
  });
  if (!res.ok) throw new Error(`Erreur UPDATE ${res.status}`);
  return res.json();
}

export const fetchResource = async <T>(
  url: string,
  setter: (data: T[]) => void,
  label: string
) => {
  try {
    const response = await fetch(url, {
      method: "GET",
      headers: getDefaultHeaders(),
    });

    const text = await response.text();

    if (response.ok) {
      const contentType = response.headers.get("Content-Type");
      if (contentType && contentType.includes("application/json")) {
        const data = JSON.parse(text);
        setter(data);
      } else {
        throw new Error("La réponse n'est pas du JSON valide");
      }
    } else {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }
  } catch (err: any) {
    throw new Error(err.message);
  }
};
