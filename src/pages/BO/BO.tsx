import { useEffect, useState } from "react";
import styles from "./BO.module.scss";
import Button from "../../components/Button/Button";
import { API_BASE_URL, API_ENDPOINTS } from "../../config/apiConfig";
import { apiDelete, apiUpdate } from "../../utils/apiHelper";
import { User, Template, Exercise } from "../../types/typesCard";
import { fetchResource } from "../../utils/apiHelper";
import { UpdateForm } from "../../components/Form/UpdateForm/UpdateForm";
import { FormDataExercise, FormDataTemplate, FormDataUser } from "../../types/FormDataType";
const fieldConfigs = {
  users: ["first_name", "last_name", "email", "role"],
  exercises: ["name", "description"],
  templates: ["name", "description", "is_public"],
} as const;

type Resource = keyof typeof fieldConfigs;

type FormData = FormDataUser | FormDataTemplate | FormDataExercise;

// PAGINATION

const itemsPerPage = 10;

const BO = () => {
  const [users, setUsers] = useState<User[]>([]);
  const [templates, setTemplates] = useState<Template[]>([]);
  const [exercices, setExercices] = useState<Exercise[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [openModal, setOpenModal] = useState(false);
  const [selectedResource, setSelectedResource] = useState<Resource | null>(
    null
  );
  const [selectedId, setSelectedId] = useState<number | null>(null);
  const [initialData, setInitialData] = useState<FormData | null>(null);
  const [newExercise, setNewExercise] = useState<FormDataExercise>({
    name: "",
    description: "",
    muscle_group_id: "",
  });
  const [adding, setAdding] = useState(false);
  const [muscleGroups, setMuscleGroups] = useState<{ id: number; name: string }[]>([]);
  const [usersPage, setUsersPage] = useState(1);
  const [templatesPage, setTemplatesPage] = useState(1);
  const [exercisesPage, setExercisesPage] = useState(1);

useEffect(() => {
  fetch(`${API_BASE_URL}${API_ENDPOINTS.getAllMuscleGroups}`)
    .then((res) => res.json())
    .then((data) => setMuscleGroups(data))
    .catch((err) => console.error("Muscle groups fetch error", err));
}, []);
  const getInitialFormData = (item: any): FormData => {
    if (
      "first_name" in item &&
      "last_name" in item &&
      "email" in item &&
      "role" in item
    ) {
      return {
        first_name: item.first_name ?? "",
        last_name: item.last_name ?? "",
        email: item.email ?? "",
        role: item.role ?? "",
      };
    } else if ("is_public" in item) {
      return {
        name: item.name ?? "",
        description: item.description ?? "",
        is_public: item.is_public ?? false,
      };
    } else {
      return {
        name: item.name ?? "",
        description: item.description ?? "",
        muscle_group_id: item.muscle_group_id ?? "",
      };
    }
  };

  const openUpdateModal = (resource: Resource, id: number) => {
    setSelectedResource(resource);
    setSelectedId(id);

    let item: User | Template | Exercise | undefined;
    if (resource === "users") item = users.find((u) => u.id === id);
    if (resource === "templates") item = templates.find((t) => t.id === id);
    if (resource === "exercises") item = exercices.find((e) => e.id === id);

    if (item) {
      setInitialData(getInitialFormData(item));
      setOpenModal(true);
    }
  };

  const handleClose = () => {
    setOpenModal(false);
  };
  const handleNewExerciseChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setNewExercise((prev) => ({ ...prev, [name]: value }));
  };

  const handleAddExercise = async (e: React.FormEvent) => {
    e.preventDefault();
    setAdding(true);
    try {
      await fetch(`${API_BASE_URL}${API_ENDPOINTS.createExercise}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(newExercise),
      });
      await fetchData("exercises");
      setNewExercise({ name: "", description: "", muscle_group_id: "" });
    } catch (err: any) {
      setError(err.message || "Erreur à l'ajout");
    } finally {
      setAdding(false);
    }
  };
  const fetchUsers = () =>
    fetchResource<User>(
      `${API_BASE_URL}${API_ENDPOINTS.getAllUsers}`,
      setUsers,
      "users"
    );
  const fetchTemplates = () =>
    fetchResource<Template>(
      `${API_BASE_URL}${API_ENDPOINTS.getAllTemplates}`,
      setTemplates,
      "templates"
    );
  const fetchExercices = () =>
    fetchResource<Exercise>(
      `${API_BASE_URL}${API_ENDPOINTS.getAllExercises}`,
      setExercices,
      "exercices"
    );

  const fetchData = async (resource: Resource) => {
    switch (resource) {
      case "users":
        return fetchUsers();
      case "templates":
        return fetchTemplates();
      case "exercises":
        return fetchExercices();
    }
  };

  const handleDelete = async (resource: Resource, id: number) => {
    try {
      await apiDelete(resource, id);
      await fetchData(resource);
    } catch (e: any) {
      setError(e.message || "Erreur lors de la suppression");
    }
  };

  const handleSubmitUpdate = async (data: FormData) => {
    if (!selectedResource || selectedId === null) return;

    let payload: any;

    switch (selectedResource) {
      case "users":
        payload = {
          first_name: (data as FormDataUser).first_name,
          last_name: (data as FormDataUser).last_name,
          email: (data as FormDataUser).email,
          role: (data as FormDataUser).role,
        };
        break;
      case "templates":
        payload = {
          name: (data as FormDataTemplate).name,
          description: (data as FormDataTemplate).description,
          is_public: (data as FormDataTemplate).is_public,
        };
        break;
      case "exercises":
        payload = {
          name: (data as FormDataExercise).name,
          description: (data as FormDataExercise).description,
        };
        break;
    }

    try {
      await apiUpdate(selectedResource, selectedId, payload);
      await fetchData(selectedResource);
      setOpenModal(false);
    } catch (e: any) {
      setError(e.message || "Erreur lors de la mise à jour");
    }
  };

  useEffect(() => {
    Promise.all([fetchUsers(), fetchTemplates(), fetchExercices()])
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, []);

  // PAGINATION
  // STARTER
const uStart = (usersPage - 1) * itemsPerPage;
const tStart = (templatesPage - 1) * itemsPerPage;
const eStart = (exercisesPage - 1) * itemsPerPage;

// FIN
const visibleUsers = users.slice(uStart, uStart + itemsPerPage);
const visibleTemplates = templates.slice(tStart, tStart + itemsPerPage);
const visibleExercises = exercices.slice(eStart, eStart + itemsPerPage);

// TOTAL
const usersTotalPages      = Math.ceil(users.length / itemsPerPage);
const templatesTotalPages  = Math.ceil(templates.length / itemsPerPage);
const exercisesTotalPages  = Math.ceil(exercices.length / itemsPerPage);
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
                  <th data-label="Id">Id</th>
                  <th data-label="First Name">First Name</th>
                  <th data-label="Last Name">Last Name</th>
                  <th data-label="Email">Email</th>
                  <th data-label="Role">Role</th>
                  <th data-label="Actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                {visibleUsers.map((user) => (
                  <tr key={user.id}>
                    <td data-label="Id">{user.id}</td>
                    <td data-label="First Name">{user.first_name}</td>
                    <td data-label="Last Name">{user.last_name}</td>
                    <td data-label="Email">{user.email}</td>
                    <td data-label="Role">{user.role}</td>
                    <td data-label="Actions">
                      <Button
                        text="✎"
                        onClick={() => openUpdateModal("users", user.id)}
                      />{" "}
                      <Button
                        text="🗑️"
                        onClick={() => handleDelete("users", user.id)}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
          <div className={styles.pagination}>
            <button
              onClick={() => setUsersPage(p => Math.max(p - 1, 1))}
              disabled={usersPage === 1}
            >
               «
            </button>
            <span>
              Page {usersPage} / {usersTotalPages}
            </span>
            <button
              onClick={() =>
                setUsersPage(p => Math.min(p + 1, usersTotalPages))
              }
              disabled={usersPage === usersTotalPages}
            >
               »
            </button>
          </div>
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
                  <th data-label="Id">Id</th>
                  <th data-label="Name">Name</th>
                  <th data-label="Description">Description</th>
                  <th data-label="Actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                {visibleTemplates.map((template) => (
                  <tr key={template.id}>
                    <td data-label="Id">{template.id}</td>
                    <td data-label="Name">{template.name}</td>
                    <td data-label="Description">{template.description}</td>
                    <td data-label="Actions">
                      <Button
                        text="✎"
                        onClick={() =>
                          openUpdateModal("templates", template.id)
                        }
                      />{" "}
                      <Button
                        text="🗑️"
                        onClick={() => handleDelete("templates", template.id)}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
          <div className={styles.pagination}>
            <button
              onClick={() => setTemplatesPage(p => Math.max(p - 1, 1))}
              disabled={templatesPage === 1}
            >
               «
            </button>
            <span>
              Page {templatesPage} / {templatesTotalPages}
            </span>
            <button
              onClick={() =>
                setTemplatesPage(p => Math.min(p + 1, templatesTotalPages))
              }
              disabled={templatesPage === templatesTotalPages}
            >
               »
            </button>
          </div>
        </section>

        <section>
          <h2>Section Exercices</h2>

          <form className={styles.addForm} onSubmit={handleAddExercise}>
            <h3>Ajouter un exercice</h3>

            <label htmlFor="exercise-name">Nom de l'exercice</label>
            <input
              id="exercise-name"
              name="name"
              placeholder="Nom de l'exercice"
              value={newExercise.name}
              onChange={handleNewExerciseChange}
              required
            />

            <label htmlFor="exercise-desc">Description</label>
            <input
              id="exercise-desc"
              name="description"
              placeholder="Description"
              value={newExercise.description}
              onChange={handleNewExerciseChange}
              required
            />

            <label htmlFor="exercise-group">Groupe musculaire</label>
            <select
              id="exercise-group"
              name="muscle_group_id"
              value={newExercise.muscle_group_id}
              onChange={(e) =>
                setNewExercise((prev) => ({
                  ...prev,
                  muscle_group_id: Number(e.target.value),
                }))
              }
              required
            >
              <option value="" disabled>
                — Sélectionnez un groupe —
              </option>
              {muscleGroups.map((g) => (
                <option key={g.id} value={g.id}>
                  {g.name}
                </option>
              ))}
            </select>

            <button type="submit" disabled={adding}>
              {adding ? "Ajout…" : "Ajouter"}
            </button>
          </form>
          {loading ? (
            <p>Chargement...</p>
          ) : error ? (
            <p style={{ color: "red" }}>{error}</p>
          ) : (
            <table>
              <thead>
                <tr>
                  <th data-label="Id">Id</th>
                  <th data-label="Name">Name</th>
                  <th data-label="Description">Description</th>
                  <th data-label="Actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                {visibleExercises.map((exercice) => (
                  <tr key={exercice.id}>
                    <td data-label="Id">{exercice.id}</td>
                    <td data-label="Name">{exercice.name}</td>
                    <td data-label="Description">{exercice.description}</td>
                    <td data-label="Actions">
                      <Button
                        text="✎"
                        onClick={() =>
                          openUpdateModal("exercises", exercice.id)
                        }
                      />{" "}
                      <Button
                        text="🗑️"
                        onClick={() => handleDelete("exercises", exercice.id)}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
          <div className={styles.pagination}>
            <button
              onClick={() => setExercisesPage(p => Math.max(p - 1, 1))}
              disabled={exercisesPage === 1}
            >
               «
            </button>
            <span>
              Page {exercisesPage} / {exercisesTotalPages}
            </span>
            <button
              onClick={() =>
                setExercisesPage(p => Math.min(p + 1, exercisesTotalPages))
              }
              disabled={exercisesPage === exercisesTotalPages}
            >
               »
            </button>
          </div>
        </section>
      </main>
      {openModal && selectedResource && initialData ? (
        <UpdateForm
          open={openModal}
          onClose={handleClose}
          initialData={initialData}
          onSubmit={handleSubmitUpdate}
          resource={selectedResource}
        />
      ) : null}
    </div>
  );
};

export default BO;
