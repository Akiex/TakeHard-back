import { useEffect, useState } from "react";
import styles from "./BO.module.scss";
import Button from "../../components/Button/Button";
import { API_BASE_URL, API_ENDPOINTS, getDefaultHeaders } from "../../config/apiConfig";

const BO = () => {
  const [users, setUsers] = useState([]);
  const [templates, setTemplates] = useState([]);
  const [exercices, setExercices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

useEffect(() => {
  const fetchUsers = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.getAllUsers}`, {
        method: "GET",
        headers: getDefaultHeaders(),
      });

      const text = await response.text();
      console.log("Réponse brute", text); // Log brute de la réponse

      // Vérifie que la réponse est en JSON
      if (response.ok) {
        const contentType = response.headers.get("Content-Type");
        if (contentType && contentType.includes("application/json")) {
          const data = JSON.parse(text); // ou response.json() si le type est correct
          setUsers(data);
        } else {
          throw new Error("La réponse n'est pas du JSON valide");
        }
      } else {
        throw new Error(`Erreur HTTP: ${response.status}`);
      }
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };
  const fetchTemplates = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.getAllTemplates}`, {
      method: "GET",
      headers: getDefaultHeaders(),
    });

    const text = await response.text();
    console.log("Réponse brute pour les templates:", text);

    if (response.ok) {
      const contentType = response.headers.get("Content-Type");
      if (contentType && contentType.includes("application/json")) {
        const data = JSON.parse(text); // ou response.json() si le type est correct
        setTemplates(data);
      } else {
        throw new Error("La réponse n'est pas du JSON valide");
      }
    } else {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }
  } catch (err: any) {
    setError(err.message);
  } finally {
    setLoading(false);
  }
};
  const fetchExercices = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}${API_ENDPOINTS.getAllExercises}`, {
      method: "GET",
      headers: getDefaultHeaders(),
    });

    const text = await response.text();
    console.log("Réponse brute pour les templates:", text);

    if (response.ok) {
      const contentType = response.headers.get("Content-Type");
      if (contentType && contentType.includes("application/json")) {
        const data = JSON.parse(text); // ou response.json() si le type est correct
        setExercices(data);
      } else {
        throw new Error("La réponse n'est pas du JSON valide");
      }
    } else {
      throw new Error(`Erreur HTTP: ${response.status}`);
    }
  } catch (err: any) {
    setError(err.message);
  } finally {
    setLoading(false);
  }
};
  fetchExercices();
  fetchTemplates();
  fetchUsers();
}, []);

  return (
    <div className={styles.BO}>
      <main>
        <section>
          <h2>Section User</h2>
          {loading ? (
            <p>Chargement...</p>
          ) : error ? (
            <p style={{ color: "red" }}>{error}</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Id</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {users.map((user: any) => (
                  <tr key={user.id}>
                    <td>{user.id}</td>
                    <td>{user.first_name}</td>
                    <td>{user.last_name}</td>
                    <td>{user.email}</td>
                    <td>{user.role}</td>
                    <td>
                      <Button text="✎" /> <Button text="🗑️" />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </section>

        <section>
          <h2>Section Template</h2>
          {loading ? (
            <p>Chargement...</p>
          ) : error ? (
            <p style={{ color: "red" }}>{error}</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Actions</th>
                  <th></th> {/* Colonne vide */}
                  <th></th> {/* Colonne vide */}
                </tr>
              </thead>
              <tbody>
                {templates.map((templates: any) => (
                  <tr key={templates.id}>
                    <td>{templates.id}</td>
                    <td>{templates.name}</td>
                    <td>{templates.description}</td>

                    <td>
                      <Button text="✎" /> <Button text="🗑️" />
                    </td>
                    <td></td> {/* Colonne vide */}
                    <td></td> {/* Colonne vide */}
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </section>

        <section>
          <h2>Section Exercices</h2>
          {loading ? (
            <p>Chargement...</p>
          ) : error ? (
            <p style={{ color: "red" }}>{error}</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Actions</th>
                  <th></th> {/* Colonne vide */}
                  <th></th> {/* Colonne vide */}
                </tr>
              </thead>
              <tbody>
                {templates.map((templates: any) => (
                  <tr key={templates.id}>
                    <td>{templates.id}</td>
                    <td>{templates.name}</td>
                    <td>{templates.description}</td>
                    <td>
                      <Button text="✎" /> <Button text="🗑️" />
                    </td>
                    <td></td> {/* Colonne vide */}
                    <td></td> {/* Colonne vide */}
                  </tr>
                ))}
              </tbody>
            </table>
          )}
        </section>

      </main>
    </div>
  );
};

export default BO;
