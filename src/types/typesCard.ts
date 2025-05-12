export interface Exercise {
  id: number;
  name: string;
  sets: number;
  reps: number;
  rest: number;
}

export interface Template {
  id: number;
  title: string;
  exercises: Exercise[];
}