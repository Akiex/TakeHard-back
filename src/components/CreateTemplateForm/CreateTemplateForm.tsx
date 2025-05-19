import React, { useEffect, useState, FormEvent } from "react";
import { API_BASE_URL, API_ENDPOINTS } from "../../config/apiConfig";
import { Template, Exercise, Set } from "../../types/typesCard";
import styles from "./../../pages/Profile/AccountPage.module.scss";

interface Entry {
  exerciseId: number;
  sets: number;
  reps: number;
  weight: number;
  rest_time: number;
}

const CreateTemplateForm: React.FC<{ userId: number; onCreated: (t: Template) => void }> = ({
  userId,
  onCreated,
}) => {
  const [exercises, setExercises] = useState<Exercise[]>([]);
  const [entries, setEntries] = useState<Entry[]>([
    { exerciseId: 0, sets: 3, reps: 10, weight: 0, rest_time: 60 },
  ]);
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [isPublic, setIsPublic] = useState(false);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetch(`${API_BASE_URL}${API_ENDPOINTS.getAllExercises}`)
      .then((res) => res.json())
      .then((data: Exercise[]) => setExercises(data))
      .catch(console.error);
  }, []);

  const addEntry = () =>
    setEntries((prev) => [
      ...prev,
      { exerciseId: 0, sets: 3, reps: 10, weight: 0, rest_time: 60 },
    ]);

  const removeEntry = (idx: number) =>
    setEntries((prev) => prev.filter((_, i) => i !== idx));

  const updateEntry = (
    idx: number,
    field: keyof Entry,
    value: string | number
  ) => {
    setEntries((prev) =>
      prev.map((e, i) =>
        i === idx ? { ...e, [field]: typeof value === "string" ? Number(value) : value } : e
      )
    );
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const createdSetIds = await Promise.all(
        entries.map(async (entry) => {
          const resp = await fetch(`${API_BASE_URL}${API_ENDPOINTS.createSet}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              sets: entry.sets,
              reps: entry.reps,
              weight: entry.weight,
              rest_time: entry.rest_time,
            }),
          });
          if (!resp.ok) throw new Error("Erreur création set");
          const json: { id: number } = await resp.json();
          return { setId: json.id, exerciseId: entry.exerciseId };
        })
      );

      const payload = {
        user_id: userId,
        name,
        description,
        is_public: isPublic,
        sets: createdSetIds.map(({ setId, exerciseId }) => ({
          id: setId,
          exercises: [{ id: exerciseId }],
        })),
      };

      const tplResp = await fetch(
        `${API_BASE_URL}${API_ENDPOINTS.createTemplate}`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        }
      );
      if (!tplResp.ok) throw new Error("Erreur création template");
      const newTemplate: Template = await tplResp.json();
      onCreated(newTemplate);

      setName("");
      setDescription("");
      setIsPublic(false);
      setEntries([{ exerciseId: 0, sets: 3, reps: 10, weight: 0, rest_time: 60 }]);
    } catch (err) {
      console.error(err);
      alert("Impossible de créer le template");
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className={styles.formContainer}>
      <h2>Créer un nouveau template</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Nom</label>
          <input
            type="text"
            required
            value={name}
            onChange={(e) => setName(e.currentTarget.value)}
          />
        </div>
        <div>
          <label>Description</label>
          <textarea
            value={description}
            onChange={(e) => setDescription(e.currentTarget.value)}
          />
        </div>
        <div>
          <label>
            <input
              type="checkbox"
              checked={isPublic}
              onChange={(e) => setIsPublic(e.currentTarget.checked)}
            />
            Public
          </label>
        </div>

        <h3>Exercices</h3>
        {entries.map((entry, idx) => (
          <div key={idx} className={styles.exerciseBlock}>
            <select
              value={entry.exerciseId}
              onChange={(e) =>
                updateEntry(idx, "exerciseId", Number(e.currentTarget.value))
              }
              required
            >
              <option value={0}>— Choisir un exercice —</option>
              {exercises.map((ex) => (
                <option key={ex.id} value={ex.id}>
                  {ex.name}
                </option>
              ))}
            </select>

            <div>
              <label>Sets</label>
              <input
                type="number"
                min={1}
                value={entry.sets}
                onChange={(e) => updateEntry(idx, "sets", e.currentTarget.value)}
              />
            </div>
            <div>
              <label>Reps</label>
              <input
                type="number"
                min={1}
                value={entry.reps}
                onChange={(e) => updateEntry(idx, "reps", e.currentTarget.value)}
              />
            </div>
            <div>
              <label>Poids (kg)</label>
              <input
                type="number"
                min={0}
                value={entry.weight}
                onChange={(e) =>
                  updateEntry(idx, "weight", e.currentTarget.value)
                }
              />
            </div>
            <div>
              <label>Repos (s)</label>
              <input
                type="number"
                min={0}
                value={entry.rest_time}
                onChange={(e) =>
                  updateEntry(idx, "rest_time", e.currentTarget.value)
                }
              />
            </div>

            <button type="button" onClick={() => removeEntry(idx)}>
              – Supprimer
            </button>
          </div>
        ))}

        <button type="button" onClick={addEntry}>
          + Ajouter un exercice
        </button>

        <button type="submit" disabled={loading}>
          {loading ? "Création…" : "Créer le template"}
        </button>
      </form>
    </section>
  );
};

export default CreateTemplateForm;
