import React, { useEffect, useState, FormEvent } from "react";
import { API_BASE_URL, API_ENDPOINTS } from "../../config/apiConfig";
import { Template, Exercise } from "../../types/typesCard";
import styles from "./CreateTemplateForm.module.scss";

interface Entry {
  exerciseId: number;
  sets: number;
  reps: number;
  weight: number;
  rest_time: number;
}

const CreateTemplateForm: React.FC<{
  userId: number;
  onCreated: (t: Template) => void;
}> = ({ userId, onCreated }) => {
  const [exercises, setExercises] = useState<Exercise[]>([]);
  const [entries, setEntries] = useState<Entry[]>([
    { exerciseId: 0, sets: 3, reps: 10, weight: 0, rest_time: 60 },
  ]);
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
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
        i === idx
          ? {
              ...e,
              [field]: typeof value === "string" ? Number(value) : value,
            }
          : e
      )
    );
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      // 1. Création des sets un par un
      const createdSetIds = await Promise.all(
        entries.map(async (entry) => {
          const resp = await fetch(
            `${API_BASE_URL}${API_ENDPOINTS.createSet}`,
            {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                sets: entry.sets,
                reps: entry.reps,
                weight: entry.weight,
                rest_time: entry.rest_time,
              }),
            }
          );
          if (!resp.ok) throw new Error("Erreur création set");
          const json: { id: number } = await resp.json();
          return { setId: json.id, exerciseId: entry.exerciseId };
        })
      );

      // 2. Construction du payload pour créer le template (is_public = false)
      const payload = {
        user_id: userId,
        name,
        description,
        is_public: false,
        sets: createdSetIds.map(({ setId, exerciseId }) => ({
          id: setId,
          exercises: [{ id: exerciseId }],
        })),
      };

      // 3. Envoi de la requête de création du template
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

      // 4. Réinitialisation du formulaire
      setName("");
      setDescription("");
      setEntries([
        { exerciseId: 0, sets: 3, reps: 10, weight: 0, rest_time: 60 },
      ]);
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
        <div className={styles.fieldGroup}>
          <label htmlFor="template-name">Nom du template</label>
          <input
            id="template-name"
            type="text"
            required
            value={name}
            onChange={(e) => setName(e.currentTarget.value)}
          />
        </div>

        <div className={styles.fieldGroup}>
          <label htmlFor="template-description">Description</label>
          <textarea
            id="template-description"
            value={description}
            onChange={(e) => setDescription(e.currentTarget.value)}
          />
        </div>



        <h3>Exercices</h3>
        {entries.map((entry, idx) => (
          <fieldset key={idx} className={styles.exerciseBlock}>
            <legend>Exercice n°{idx + 1}</legend>

            <div className={styles.fieldGroup}>
              <label htmlFor={`exercise-select-${idx}`}>Choisir un exercice</label>
              <select
                id={`exercise-select-${idx}`}
                value={entry.exerciseId}
                onChange={(e) =>
                  updateEntry(idx, "exerciseId", Number(e.currentTarget.value))
                }
                required
              >
                <option value={0}>— Sélectionner un exercice —</option>
                {exercises.map((ex) => (
                  <option key={ex.id} value={ex.id}>
                    {ex.name}
                  </option>
                ))}
              </select>
            </div>

            <div className={styles.inlineFields}>
              <div className={styles.fieldGroup}>
                <label htmlFor={`sets-${idx}`}>Sets</label>
                <input
                  id={`sets-${idx}`}
                  type="number"
                  min={1}
                  value={entry.sets}
                  onChange={(e) =>
                    updateEntry(idx, "sets", e.currentTarget.value)
                  }
                  required
                />
              </div>
              <div className={styles.fieldGroup}>
                <label htmlFor={`reps-${idx}`}>Reps</label>
                <input
                  id={`reps-${idx}`}
                  type="number"
                  min={1}
                  value={entry.reps}
                  onChange={(e) =>
                    updateEntry(idx, "reps", e.currentTarget.value)
                  }
                  required
                />
              </div>
              <div className={styles.fieldGroup}>
                <label htmlFor={`weight-${idx}`}>Poids (kg)</label>
                <input
                  id={`weight-${idx}`}
                  type="number"
                  min={0}
                  value={entry.weight}
                  onChange={(e) =>
                    updateEntry(idx, "weight", e.currentTarget.value)
                  }
                />
              </div>
              <div className={styles.fieldGroup}>
                <label htmlFor={`rest-${idx}`}>Repos (s)</label>
                <input
                  id={`rest-${idx}`}
                  type="number"
                  min={0}
                  value={entry.rest_time}
                  onChange={(e) =>
                    updateEntry(idx, "rest_time", e.currentTarget.value)
                  }
                />
              </div>
            </div>

            <button
              type="button"
              className={styles.removeButton}
              onClick={() => removeEntry(idx)}
            >
              – Supprimer cet exercice
            </button>
          </fieldset>
        ))}

        <button
          type="button"
          className={styles.addButton}
          onClick={addEntry}
        >
          + Ajouter un exercice
        </button>

        <button type="submit" disabled={loading} className={styles.submitButton}>
          {loading ? "Création…" : "Créer le template"}
        </button>
      </form>
    </section>
  );
};

export default CreateTemplateForm;
