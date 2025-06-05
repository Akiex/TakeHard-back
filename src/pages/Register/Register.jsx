import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { createUser } from "../../services/api";
import Button from "../../components/Button/Button";
import styles from "./Register.module.scss";

const Register = () => {
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [role, setRole] = useState("user");

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState("");
  const [successMessage, setSuccessMessage] = useState("");

  const navigate = useNavigate();

  useEffect(() => {
    if (successMessage) {
      const timer = setTimeout(() => {
        navigate("/login");
      }, 3000);
      return () => clearTimeout(timer);
    }
  }, [successMessage, navigate]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (isSubmitting) return;

    if (password !== confirmPassword) {
      setErrorMessage("Les mots de passe ne correspondent pas.");
      setSuccessMessage("");
      return;
    }
    setErrorMessage("");
    setIsSubmitting(true);

    try {
      const userData = {
        first_name: firstName,
        last_name: lastName,
        email,
        password,
        role,
      };

      await createUser(userData);
      setSuccessMessage(
        "Inscription réussie ! Vous allez être redirigé(e) vers la page de connexion."
      );
      setErrorMessage("");

      // Réinitialiser les champs
      setFirstName("");
      setLastName("");
      setEmail("");
      setPassword("");
      setConfirmPassword("");
      setRole("user");
    } catch (error) {
      console.error("Erreur lors de l'inscription :", error);
      setErrorMessage(error.message || "Une erreur est survenue lors de l'inscription.");
      setSuccessMessage("");
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

          <label htmlFor="confirmPassword">Confirmer le mot de passe :</label>
          <input
            type="password"
            name="confirmPassword"
            id="confirmPassword"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
            required
          />

          {errorMessage && <p className={styles.error}>{errorMessage}</p>}
          {successMessage && <p className={styles.success}>{successMessage}</p>}

          <label htmlFor="confidential">
            J'accepte la{" "}
            <a
              href="/confidentialite"
              target="_blank"
              rel="noopener noreferrer"
            >
              politique de confidentialité
            </a>
          </label>
          <input
            type="checkbox"
            name="confidential"
            id="confidential"
            required
          />

          <label htmlFor="cgu">
            J'accepte les{" "}
            <a
              href="/conditions-generales"
              target="_blank"
              rel="noopener noreferrer"
            >
              CGU
            </a>
          </label>
          <input type="checkbox" name="cgu" id="cgu" required />

          <Button
            text={isSubmitting ? "En cours..." : "S'inscrire"}
            variant="secondary"
            type="submit"
            disabled={isSubmitting}
          />
        </form>
      </div>
    </div>
  );
};

export default Register;
