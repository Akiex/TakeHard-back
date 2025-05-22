import React, { useMemo } from "react";
import styles from "./UserTemplateCard.module.scss";
import { Template, Exercise, Set } from "../../../types/typesCard";

interface Props {
  template: Template;
  isSelected: boolean;
  onSelect: () => void;
}

// Amplitudes moyennes estimées par exercice (en mètres)
const amplitudes: Record<string, number> = {
  "Tractions pronation": 0.4,
  "Curl biceps": 0.3,
  "Soulevé de terre": 0.6,
  "Rowing barre": 0.5,
  "Hip Thrust": 0.3,
  "Presse inclinée": 0.6,
};

const efficience = 0.15; // on baisse pour augmenter l’estimation
const gravite = 9.81;

function calcCalories(
  weightKg: number,
  reps: number,
  setsCount: number,
  amplitudeM: number,
  overhead = 1.7 // facteur à ajuster entre 1.5 et 2
) {
  const force = weightKg * gravite;
  const travail = force * amplitudeM * reps * setsCount;
  const depenseEnergieJ = travail / efficience;
  const depenseKcal = depenseEnergieJ / 4184;
  return depenseKcal * overhead;
}

const UserTemplateCard: React.FC<Props> = ({
  template,
  isSelected,
  onSelect,
}) => {
  if (
    !template ||
    !Array.isArray(template.sets) ||
    template.sets.length === 0
  ) {
    return <div className={styles.card}>Aucune donnée à afficher.</div>;
  }

  // Regrouper les sets par exercice
  const exerciseMap: Record<number, { exercise: Exercise; sets: Set[] }> = {};
  template.sets.forEach((set) => {
    set.exercises.forEach((exercise) => {
      if (!exerciseMap[exercise.id]) {
        exerciseMap[exercise.id] = { exercise, sets: [] };
      }
      exerciseMap[exercise.id].sets.push(set);
    });
  });
  const exercisesWithSets = Object.values(exerciseMap);

  // Calculer le total des calories AVANT le rendu
  const totalCalories = useMemo(() => {
    return exercisesWithSets.reduce((sum, { exercise, sets }) => {
      const amplitudeM = amplitudes[exercise.name] ?? 0.4;
      const caloriesExo = sets.reduce(
        (acc, s) => acc + calcCalories(s.weight, s.reps, s.sets, amplitudeM),
        0
      );
      return sum + caloriesExo;
    }, 0);
  }, [exercisesWithSets]);

  return (
    <div
      className={`${styles.card} ${isSelected ? styles.selected : ""}`}
      onClick={onSelect}
    >
      <h4 className={styles.title}>{template.name}</h4>
      <p className={styles.description}>{template.description}</p>

      {exercisesWithSets.map(({ exercise, sets }) => (
        <div key={exercise.id} className={styles.exerciseBlock}>
          <h5 className={styles.exerciseTitle}>{exercise.name}</h5>
          <ul className={styles.setList}>
            {sets.map((s) => (
              <li key={s.id} className={styles.setItem}>
                {s.sets}×{s.reps} : {s.weight}kg – repos {s.rest_time}s
              </li>
            ))}
          </ul>
        </div>
      ))}

      <h5 className={styles.total}>
        Total dépense estimée séance : {Math.round(totalCalories)} kcal
      </h5>
    </div>
  );
};

export default UserTemplateCard;
