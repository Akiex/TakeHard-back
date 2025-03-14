const API_URL = process.env.REACT_APP_API_URL;
const token = localStorage.getItem("token");
console.log("Token récupéré :", token);

export const createUser = async (userData) => {
  const response = await fetch(`${API_URL}/users`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(userData),
  });

  if (!response.ok) {
    const errorData = await response.text(); // Pour loguer la réponse complète de l'API en cas d'erreur
    console.error("Réponse API en cas d'échec :", errorData);
    throw new Error("Erreur lors de l'inscription");
  }

  return response.json();
};
