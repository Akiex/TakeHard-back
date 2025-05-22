import React from "react";
import styles from "./Card.module.scss";
import { Template, Exercise, Set } from "../../types/typesCard";

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
/******************************************************************/
/*
/* NB : PENSEZ A RAJOUTER DES AMPLITUDES POUR TOUT LES EXERCICES */
/*
/****************************************************************/
const efficience = 0.15;
const gravite = 9.81;

function calcCalories(
  weightKg: number,
  reps: number,
  setsCount: number,
  amplitudeM: number,
  overhead = 1.7
) {
  const force = weightKg * gravite;
  const travail = force * amplitudeM * reps * setsCount;
  const depenseEnergieJ = travail / efficience;
  const depenseKcal = depenseEnergieJ / 4184;
  return depenseKcal * overhead;
}

const CardTemplate: React.FC<Props> = ({ template, isSelected, onSelect }) => {
  if (!template || !template.sets?.length) {
    return <div>Aucune donnée à afficher.</div>;
  }

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

  let totalCalories = 0;

  return (
    <div className={` ${isSelected ? styles.selected : ""}`} onClick={onSelect}>
      <h4>{template.name}</h4>
      <p>{template.description}</p>

      {exercisesWithSets.map(({ exercise, sets }) => {
        const amplitudeM = amplitudes[exercise.name] ?? 0.4;

        const caloriesExercice = sets.reduce((acc, s) => {
          return acc + calcCalories(s.weight, s.reps, s.sets, amplitudeM);
        }, 0);

        totalCalories += caloriesExercice;

        return (
          <div key={exercise.id} className={styles.exerciseBlock}>
            <h5 className={`${styles.test}`}>{exercise.name}</h5>
            <ul className={` ${styles.setList} ${styles.test}`}>
              {sets.map((s) => (
                <li
                  key={s.id}
                  className={styles.setItem}
                  style={{ listStyle: "none" }}
                >
                  {s.sets}×{s.reps} : {s.weight}kg – repos {s.rest_time}s
                </li>
              ))}
            </ul>
          </div>
        );
      })}

      <h5>Total dépense estimée séance : {totalCalories.toFixed(2)} kcal</h5>
    </div>
  );
};

export default CardTemplate;
