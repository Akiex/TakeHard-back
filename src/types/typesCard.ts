export interface MuscleGroup {
  id: number;
  name: string;
}
export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  role: string;
}
export interface Exercise {
  id: number;
  name: string;
  description: string;
  muscle_groups: MuscleGroup[];
}

export interface Set {
  id: number;
  weight: number;
  sets: number;
  reps: number;
  rest_time: number;
  exercises: Exercise[];
}

export interface Template {
  id: number;
  user_id: number;
  name: string;
  description: string;
  is_public: boolean;
  sets: Set[];
}
