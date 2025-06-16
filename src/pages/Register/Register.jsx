import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { createUser } from "../../services/api";
import Button from "../../components/Button/Button";
import styles from "./Register.module.scss";

const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const passwordRules = {
  minLength: 8,
  hasUpperCase: /[A-Z]/,
  hasLowerCase: /[a-z]/,
  hasNumber: /[0-9]/,
  hasSpecialChar: /[!@#$%^&*(),.?":{}|<>]/,
};

const Register = () => {
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [role, setRole] = useState("user");

  const [errors, setErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);
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

  const validate = () => {
    const newErrors = {};

    if (!firstName.trim()) newErrors.firstName = "Le prénom est requis.";
    if (!lastName.trim()) newErrors.lastName = "Le nom est requis.";

    if (!emailRegex.test(email)) {
      newErrors.email = "Adresse email invalide.";
    }

    if (password.length < passwordRules.minLength) {
      newErrors.password = `Le mot de passe doit faire au moins ${passwordRules.minLength} caractères.`;
    } else {
      if (!passwordRules.hasUpperCase.test(password)) {
        newErrors.password = "Le mot de passe doit contenir au moins une majuscule.";
      }
      if (!passwordRules.hasLowerCase.test(password)) {
        newErrors.password = "Le mot de passe doit contenir au moins une minuscule.";
      }
      if (!passwordRules.hasNumber.test(password)) {
        newErrors.password = "Le mot de passe doit contenir au moins un chiffre.";
      }
      if (!passwordRules.hasSpecialChar.test(password)) {
        newErrors.password = "Le mot de passe doit contenir au moins un caractère spécial.";
      }
    }

    if (password !== confirmPassword) {
      newErrors.confirmPassword = "Les mots de passe ne correspondent pas.";
    }

    return newErrors;
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (isSubmitting) return;

    const validationErrors = validate();
    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      return;
    }

    setErrors({});
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

      // Réinitialiser les champs
      setFirstName("");
      setLastName("");
      setEmail("");
      setPassword("");
      setConfirmPassword("");
      setRole("user");
    } catch (error) {
      console.error("Erreur lors de l'inscription :", error);
      setErrors({ api: error.message || "Une erreur est survenue lors de l'inscription." });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div>
      <div className={styles.registerForm}>
        <h2>Inscription</h2>
        <form onSubmit={handleSubmit} noValidate>
          <label htmlFor="firstName">Prénom :</label>
          <input
            type="text"
            name="firstName"
            id="firstName"
            value={firstName}
            onChange={(e) => setFirstName(e.target.value)}
            required
          />
          {errors.firstName && <p className={styles.error}>{errors.firstName}</p>}

          <label htmlFor="lastName">Nom :</label>
          <input
            type="text"
            name="lastName"
            id="lastName"
            value={lastName}
            onChange={(e) => setLastName(e.target.value)}
            required
          />
          {errors.lastName && <p className={styles.error}>{errors.lastName}</p>}

          <label htmlFor="email">Email :</label>
          <input
            type="email"
            name="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
          {errors.email && <p className={styles.error}>{errors.email}</p>}

          <label htmlFor="password">Mot de passe :</label>
          <input
            type="password"
            name="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          {errors.password && <p className={styles.error}>{errors.password}</p>}

          <label htmlFor="confirmPassword">Confirmer le mot de passe :</label>
          <input
            type="password"
            name="confirmPassword"
            id="confirmPassword"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
            required
          />
          {errors.confirmPassword && <p className={styles.error}>{errors.confirmPassword}</p>}

          {errors.api && <p className={styles.error}>{errors.api}</p>}

          <label htmlFor="confidential">
            J'accepte la <a href="/confidentialite" target="_blank" rel="noopener noreferrer">politique de confidentialité</a>
          </label>
          <input type="checkbox" name="confidential" id="confidential" required />

          <label htmlFor="cgu">
            J'accepte les <a href="/conditions-generales" target="_blank" rel="noopener noreferrer">CGU</a>
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
