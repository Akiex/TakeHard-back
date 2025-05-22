import { useState } from "react";
import { createUser } from "../../services/api";
import Button from "../../components/Button/Button";
import styles from "./Register.module.scss";
import { API_BASE_URL } from "../../config/apiConfig";
import { API_ENDPOINTS } from "../../config/apiConfig";

const Register = () => {
  // États pour les champs du formulaire
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("user"); // Rôle par défaut
  const [isSubmitting, setIsSubmitting] = useState(false); // État pour empêcher les doubles soumissions

  // Gestionnaire de soumission du formulaire
  const handleSubmit = async (event) => {
    event.preventDefault();

    // Bloque les appels multiples
    if (isSubmitting) {
      return;
    }

    // Active l'état "en cours de soumission"
    setIsSubmitting(true);

    try {
      const userData = {
        first_name: firstName,
        last_name: lastName,
        email,
        password,
        role,
      };

      // Appel à l'API via le service
      const data = await createUser(userData);
      console.log("Inscription réussie :", data);

      // Réinitialisation des champs après succès
      setFirstName("");
      setLastName("");
      setEmail("");
      setPassword("");
      setRole("user");
    } catch (error) {
      console.error("Erreur lors de l'inscription :", error.message);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div>
      <div className={styles.registerForm}>
        <h2>Inscription</h2>
        <form onSubmit={handleSubmit}>
          <label htmlFor="firstName">Prénom :</label>
          <input
            type="text"
            name="firstName"
            id="firstName"
            value={firstName}
            onChange={(e) => setFirstName(e.target.value)}
            required
          />

          <label htmlFor="lastName">Nom :</label>
          <input
            type="text"
            name="lastName"
            id="lastName"
            value={lastName}
            onChange={(e) => setLastName(e.target.value)}
            required
          />

          <label htmlFor="email">Email :</label>
          <input
            type="email"
            name="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />

          <label htmlFor="password">Mot de passe :</label>
          <input
            type="password"
            name="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />

          <Button text="S'inscrire" variant="secondary" type="submit" />
        </form>
      </div>
    </div>
  );
};

export default Register;
