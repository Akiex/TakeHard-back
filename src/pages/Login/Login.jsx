import { useState } from "react";
import Button from "../../components/Button/Button";
import styles from "./Login.module.scss";

const Login = () => {
  // États pour stocker les données du formulaire
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  // Fonction de soumission du formulaire
  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await fetch(
        `${process.env.REACT_APP_API_URL}/users/login`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ email, password }),
        }
      );

      if (!response.ok) {
        throw new Error("Échec de la connexion. Vérifiez vos identifiants.");
      }

      const data = await response.json();
      console.log("Connexion réussie : ", data);
      // Stocker le token ou rediriger l'utilisateur
      localStorage.setItem("token", data.token);
    } catch (error) {
      console.error("Erreur :", error.message);
    }
  };

  return (
    <div>
      <h2>Connexion</h2>
      <div className={styles.loginForm}>
        <form onSubmit={handleSubmit}>
          <label htmlFor="email">Email : </label>
          <input
            type="email"
            name="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
          <label htmlFor="password">Mot de passe : </label>
          <input
            type="password"
            name="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          <Button text="Se connecter" variant="secondary" />
        </form>
      </div>
    </div>
  );
};

export default Login;
