// components/UpdateForm.tsx
import FormControl from "@mui/material/FormControl";
import TextField from "@mui/material/TextField";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogTitle from "@mui/material/DialogTitle";
import DialogContent from "@mui/material/DialogContent";
import DialogActions from "@mui/material/DialogActions";
import { useState } from "react";
import styles from "./UpdateForm.module.scss";

const fieldConfigs = {
  users: ["first_name", "last_name", "email", "role"],
  exercises: ["name", "description"],
  templates: ["name", "description", "is_public"],
  sets: ["weight", "sets", "reps", "rest_time"],
} as const;

type UpdateFormData = {
  users: { first_name: string; last_name: string; email: string; role: string };
  exercises: { name: string; description: string };
  templates: { name: string; description: string; is_public: boolean };
  sets: { weight: number; sets: number; reps: number; rest_time: number };
};

type Resource = keyof typeof fieldConfigs;

type UpdateFormProps =
  | {
      resource: "users";
      open: boolean;
      onClose: () => void;
      initialData: UpdateFormData["users"];
      onSubmit: (data: UpdateFormData["users"]) => void;
    }
  | {
      resource: "exercises";
      open: boolean;
      onClose: () => void;
      initialData: UpdateFormData["exercises"];
      onSubmit: (data: UpdateFormData["exercises"]) => void;
    }
  | {
      resource: "templates";
      open: boolean;
      onClose: () => void;
      initialData: UpdateFormData["templates"];
      onSubmit: (data: UpdateFormData["templates"]) => void;
    }
  | {
      resource: "sets";
      open: boolean;
      onClose: () => void;
      initialData: UpdateFormData["sets"];
      onSubmit: (data: UpdateFormData["sets"]) => void;
    };
export function UpdateForm<R extends Resource>(props: {
  resource: R;
  open: boolean;
  onClose: () => void;
  initialData: UpdateFormData[R];
  onSubmit: (data: UpdateFormData[R]) => void;
}) {
  const { open, onClose, initialData, onSubmit, resource } = props;
  const [formData, setFormData] = useState<UpdateFormData[R]>(initialData);

  const handleChange = (field: string, value: string) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = () => {
    onSubmit(formData);
    onClose();
  };

  return (
    <Dialog open={open} onClose={onClose} className={styles.updateDialog}>
      <DialogTitle>Mettre à jour</DialogTitle>
      <DialogContent>
        <form className={styles.dialogForm}>
          <FormControl fullWidth>
            {fieldConfigs[resource].map((field) => (
              <TextField
                key={field}
                label={field.charAt(0).toUpperCase() + field.slice(1)}
                value={(formData as any)[field] ?? ""}
                onChange={(e) => handleChange(field, e.target.value)}
                fullWidth
                style={{ marginBottom: "0.8rem" }}
              />
            ))}
          </FormControl>
        </form>
      </DialogContent>
      <DialogActions className={styles.dialogActions}>
        <Button onClick={onClose}>Annuler</Button>
        <Button variant="contained" onClick={handleSubmit}>
          Mettre à jour
        </Button>
      </DialogActions>
    </Dialog>
  );
}
