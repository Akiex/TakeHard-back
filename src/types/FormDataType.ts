export type FormDataUser = {
  first_name: string;
  last_name: string;
  email: string;
  role: string;
};

export type FormDataTemplate = {
  name: string;
  description: string;
  is_public: boolean;
};

export type FormDataExercise = {
  name: string;
  description: string;
  muscle_group_id: number | "";
};